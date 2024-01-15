<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\Css2XPathService;

/**
 * Website posts scrapping.
 *
 * Get the posts contents as an array from website using CSS selectors.
 * Logic is designed for 'classic' layout of posts in website. It means:
 * Website has page, where all posts can be found via link based pagination.
 *
 * To start the scrappping, initialize object with base website url as a parameter.
 * Than, few base method should be called to configure scrapping behavior:
 * - post() - sets the selector for 'post cart' within posts page;
 * - postLink() - set the selector for link which must lead to post page;
 * - pagination() - set the seelctor for pagination links;
 * - value() - set the selector for target value to be scraped;
 * - shot() - set the selector for screenshot;
 * - scrape() - start the scrapping.
 *
 * Algorithm breakdown:
 * 1. go to base url;
 * 2. find 'post cart';
 * 3. go to post page and scrape values;
 * 4. check posts pagination and find next non-scraped page;
 * 5. go to next page;
 * 6. repeat steps 2-5 untill there is no non-scraped pages found in pagination.
 *
 * Can be used without pagination.
 *
 * There is other helper method to tweak the behavior:
 * - abortOnPageError() - Enable\disable exception when page can not be downloaded;
 * - nullableValues() - Markes all values as nullable;
 * - debug() - Enable\disable scrapping logging;
 * - logUsing() - Define who logging logic;
 * - limit() - Set the limit;
 * - sleep() - Defile wait seconds before page will be downloaded;
 * - count() - Count pages instead on scrapping. Use instead of scrape();
 *
 * See detailed usage, parameters and return values in method comments.
 *
 * TODO: make possible to scrape one url.
 *
 */
class PostScraperService
{
    private string $url = '';
    private string $paginationSelector = '';
    private string $postSelector;
    private string $postLinkSelector;
    private string $postLinkAttribute;
    private string $currentUrl = '';
    private bool $abortOnPageError = true;
    private bool $nullableValues = false;
    private bool $onlyCount = false;
    private bool $debug = false;
    private int $limitResult = 0;
    private int $sleep = 0;
    private string $shotDir = '';
    private array $values = [];
    private array $shots = [];
    private array $result = [];
    private array $ignoreUrls = [];
    private \Closure $logUsingClosure;
    private array $meta = [
        'parsed_posts' => [],
        'parsed_pages' => [],
        'parsed_pages_total' => 0,
        'parsed_posts_count' => 0
    ];

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->shotDir = storage_path('browsershot');
    }

    public static function make(string $url)
    {
        return new self($url);
    }

    public function logUsing(\Closure $callback)
    {
        $this->logUsingClosure = $callback;

        return $this;
    }

    public function abortOnPageError(bool $abort)
    {
        $this->abortOnPageError = $abort;

        return $this;
    }

    public function nullableValues()
    {
        $this->nullableValues = true;

        foreach ($this->values as $name => &$data) {
            $data['required'] = false;
        }

        return $this;
    }

    /**
     * Set debug flag
     *
     * @param bool $debug Debug flag
     *
     * @return self
     */
    public function debug(bool $debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * Set limit of scraped posts
     * Ignored if count() used
     *
     * @param int $limit Limit
     *
     * @return self
     */
    public function limit(int $limit)
    {
        if ($limit) {
            $this->limitResult = $limit;
        }

        return $this;
    }

    /**
     * Set urls to be ignored when scrapping
     *
     * @param array $urls Array of url to be ignored
     *
     * @return self
     */
    public function ignoreUrls(array $urls)
    {
        $this->ignoreUrls = $urls;

        return $this;
    }

    /**
     * Set pause before making GET request
     *
     * @param int $sleep Pause seconds
     *
     * @return self
     */
    public function sleep(int $sleep)
    {
        $this->sleep = $sleep;

        return $this;
    }

    /**
     * Set the selector for posts page pagination.
     *
     * @param string $selector Selector for post
     *
     * @return self
     */
    public function pagination($selector)
    {
        $this->paginationSelector = $selector;

        return $this;
    }

    /**
     * Set the selector for post element (e.g. with post link/title/thumb) withing posts page.
     *
     * @param string $selector Selector for post
     *
     * @return self
     */
    public function post($selector)
    {
        $this->postSelector = $selector;

        return $this;
    }

    /**
     * Sets the selector post page link
     *
     * @param string $selector Selector of links
     * @param string $attr Attribute where link can be found. href is default
     *
     * @return self
     */
    public function postLink($selector, $attr='href')
    {
        $this->postLinkSelector = $selector;
        $this->postLinkAttribute = $attr;

        return $this;
    }

    /**
     * Sets the selector for post field. Eg for title or description.
     *
     * @param string $name Name of field
     * @param string $selector Selector where field value can be found
     * @param string|null $attribute Attribute of value. If not supplied, content of tag will be scraped
     * @param bool|null $isMultiple Defines if scraped value must be an array
     * @param bool|null $getFromPostsPage Defines if value should be scraped from posts page instead on separate post page
     * @param bool|null $required Enable error if value is empty
     *
     * @return self
     */
    public function value(string $name, string $selector, string $attribute=null, bool $isMultiple=false, bool $getFromPostsPage=false, bool $required=true)
    {
        $this->values[$name] = [
            'required' => $this->nullableValues ? false : $required,
            'selector' => $selector,
            'is_multiple' => $isMultiple,
            'attribute' => $attribute,
            'from_posts_page' => $getFromPostsPage
        ];

        return $this;
    }

    public function shot(string $name, string $selector, bool $required=false, string $css='')
    {
        $this->shots[$name] = [
            'required' => $required,
            'selector' => $selector,
            'css' => $css
        ];

        return $this;
    }

    /**
     * Run the scraper but only count pages and posts
     *
     * @param bool $includeMeta Flag wether to include additonal data about scrappping process or no
     * @return array Returs scraped posts as array (and additional data if paramere applied)
     */
    public function scrape($includeMeta=false)
    {
        $this->scrapeHelper($this->url);
        $this->sumarizeMeta();

        return $includeMeta ? ['data' => $this->result, 'meta' => $this->meta] : $this->result;
    }

    /**
     * Run the scraper but only count pages and posts
     *
     * @return array Count resut
     */
    public function count()
    {
        $this->onlyCount = true;
        $this->scrapeHelper($this->url);

        return [
            'posts' => count($this->meta['parsed_posts']),
            'pages' => count($this->meta['parsed_pages'])
        ];
    }

    /**
     * Format and calculate scraping data and timungs
     *
     * @return void
     */
    private function sumarizeMeta()
    {
        $this->meta['parsed_pages_total'] = count($this->meta['parsed_pages']);
        $this->meta['parsed_pages_time'] = 0;
        foreach ($this->meta['parsed_pages'] as $url => $data) {
            $time = $data['end'] - $data['start'];
            $this->meta['parsed_pages'][$url]['time'] = $time;
            $this->meta['parsed_pages_time'] += $time;
        }

        $this->meta['parsed_posts_time'] = 0;
        foreach ($this->meta['parsed_posts'] as $url => $data) {
            $time = $data['end'] - $data['start'];
            $this->meta['parsed_posts'][$url]['time'] = $time;
            $this->meta['parsed_posts_time'] += $time;
        }
        $this->meta['parsed_posts_count'] = count($this->meta['parsed_posts']);

        $this->meta['avg_time_per_post'] = $this->meta['parsed_posts_time'] / $this->meta['parsed_posts_count'];
        $this->meta['avg_time_per_page'] = $this->meta['parsed_pages_time'] / $this->meta['parsed_pages_total'];
    }

    /**
     * Recursive function for site scraping
     * Determined pagination links and iterates through them.
     *
     * @param string $url Page url to start scraping
     *
     * @return void
     */
    public function scrapeHelper($url, $page=1)
    {
        $this->log("scrapeHelper $url");
        $this->meta['parsed_pages'][$url] = [
            'start' => microtime(true)
        ];
        $html = $this->getHTML($url);

        $this->log(" HTML: $html");
        $postsNodeLists = $this->querySelector($html, $this->postSelector);

        // scrape posts
        foreach ($postsNodeLists as $i => $postNode) {
            $postUrl = $this->querySelector($postNode, $this->postLinkSelector)->item(0)->getAttribute($this->postLinkAttribute)??null;

            $this->log("  Post #$page:" . $i+1 . " process: $postUrl");

            if (!$postUrl) {
                $this->log("    NOT URL");
                if ($this->abortOnPageError) {
                    throw new \Exception("Post #" . $i+1 . " url at '$url' can not be retrived", 1);
                }
                continue;
            }

            if (in_array($postUrl, $this->ignoreUrls)) {
                continue;
            }

            if ($this->onlyCount) {
                $this->meta['parsed_posts'][] = 1;
                continue;
            }

            // ensure that link contains schema and domain
            $postUrl = $this->ensureSchema($postUrl);

            $this->meta['parsed_posts'][$postUrl] = [
                'start' => microtime(true)
            ];

            // scrape values from post page
            $this->result[$postUrl] = $this->processPost($postUrl);

            // scrape values from post preview block
            foreach ($this->values as $key => $data) {
                if (!$data['from_posts_page']) {
                    continue;
                }

                $this->result[$postUrl][$key] = $this->scrapeValue($postNode, $key, $data);
            }

            $this->meta['parsed_posts'][$postUrl]['end'] = microtime(true);

            if ($this->enough()) {
                $this->log("    ENOUGH");
                break;
            }
        }

        $this->log("  Posts process done");

        // find next page url
        $paginationUrls = $this->paginationSelector
            ? $this->getLinksFromUrl($html, $this->paginationSelector)
            : [];

        foreach ($paginationUrls as $paginationUrl) {
            if (isset($this->meta['parsed_pages'][$paginationUrl])) {
                continue;
            }

            if (filter_var($paginationUrl, FILTER_VALIDATE_URL) === false) {
                continue;
            }

            $nextPageUrl = $paginationUrl;
            $this->log("  Next page url found: $nextPageUrl");
            break;
        }

        $this->meta['parsed_pages'][$url]['end'] = microtime(true);

        // check is next page must be scraped
        if ($this->enough() || !isset($nextPageUrl)) {
            $this->log("  ENOUGH or NO PAGE");
            return;
        }

        // scrape next page
        return $this->scrapeHelper($nextPageUrl, $page+1);
    }

    /**
     * Scrape all configured values from page.
     *
     * @param string $url Page HTML
     *
     * @return array scraped values
     */
    private function processPost($url)
    {
        $this->log("    Post process start $url");
        $this->currentUrl = $url;

        $html = $this->getHTML($url);
        $this->log("    HTML: $html");
        $values = [];

        foreach ($this->values as $key => $valueData) {
            $this->log("      value '$key'", $valueData);

            if ($valueData['from_posts_page']) {
                continue;
            }

            $values[$key] = $this->scrapeValue($html, $key, $valueData);
        }

        foreach ($this->shots as $key => $shotData) {
            $values[$key] = $this->shotHelper($url, $key, $shotData);
        }

        return $values;
    }

    private function shotHelper($url, $key, $shotData)
    {
        $this->log("      shot for '$key'", $shotData);

        $selector = $shotData['selector'];
        $result = [];

        for ($i=0; $i < 5; $i++) {
            try {
                $name = $key . '-' . time() . '.jpeg';
                $path = $this->shotDir . '/' . $name;

                $browserhot = \Spatie\Browsershot\Browsershot::url($url)
                    ->select($selector, $i)
                    ->setScreenshotType('jpeg', 100)
                    ->newHeadless();
                
                if ($shotData['css']) {
                    $browserhot->setOption('addStyleTag', json_encode(['content' => $shotData['css']]));
                }

                $browserhot->save($path);
                    
                $result[] = $path;
                $this->log("        done #$i");
            } catch (\Spatie\Browsershot\Exceptions\ElementNotFound $th) {
                if ($shotData['required'] && $i==1) {
                    throw new \Exception("Can not make a required shot for '$key' ($selector) at $url", 1);
                }
                $this->log("        error #$i (ElementNotFound): " . $th->getMessage());
                break;
            }
        }

        $this->log("        result", $result);

        return $result;
    }

    /**
     * Get tag or multiple tags from provided html.
     * Call helper to scrape value from tag(s)
     *
     * @param $html Page HTML
     * @param string $key Value name
     * @param array $data Data of how the value must be scraped
     *
     * @return string $res Scraped value
     */
    private function scrapeValue($html, $key, $valueData)
    {
        $nodeList = $this->querySelector($html, $valueData['selector']);

        $this->log("        selector '{$valueData['selector']}'");

        if ($valueData['is_multiple']) {
            $this->log("        is multiple");
            $res = [];
            foreach ($nodeList as $node) {
                $res[] = $this->scrapeValueHelper($node, $key, $valueData);
            }
            $this->log("        result: ", $res);
        } else {
            $this->log("        is not multiple");
            $node = $nodeList->item(0);
            $res = $this->scrapeValueHelper($node, $key, $valueData);
            $this->log("        result: $res");
        }

        return $res;
    }

    /**
     * Scrape value from tag.
     * Get inner html or arratibite value.
     *
     * @param $node Tag to be scraped
     * @param string $key Value name
     * @param array $data Data of how the value must be scraped
     *
     * @return string $res Scraped value
     */
    private function scrapeValueHelper($node, $key, $valueData)
    {
        $attr = $valueData['attribute'];
        $res = '';

        if ($attr && $attr == 'html') {
            $res = $node?->ownerDocument->saveHtml($node);
            $res = $this->crearHtml($res)??null;
        } else if ($attr) {
            $res = $node->getAttribute($attr)??null;
        } else {
            $res = $node->nodeValue??null;
        }

        if (!$res && $valueData['required']) {
            throw new \Exception("Empty value encounered for required '$key' value ({$valueData['selector']}) at $this->currentUrl", 1);
        }

        if ($res && $attr && in_array($attr, ['href', 'src'])) {
            $res = $this->ensureSchema($res);
        }

        return $res;
    }

    /**
     * Remove arbitrary attributes from html
     *
     * @param string $html Input html
     *
     * @return string $html Cleaned html
     */
    private function crearHtml($html)
    {
        if (!$html) return $html;

        $dom = new \DOMDocument;
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('.//*[@id]|.//*[@class]|.//*[@style]');
        foreach ($nodes as $node) {
            $node->removeAttribute('id');
            $node->removeAttribute('class');
            $node->removeAttribute('style');
        }

        $html = $dom->saveHTML();
        $pos = strpos($html, '<body>');
        $html = substr($html, $pos+6);
        $pos = strpos($html, '</body>');
        $html = substr($html, 0, $pos);

        return $html;
    }

    private function getLinksFromUrl($html, $selector)
    {
        $nodeList = $this->querySelector($html, $selector);
        $links = [];

        foreach ($nodeList as $node) {
            $href = $node->getAttribute('href');
            $links[] = $this->ensureSchema($href);
        }

        return $links;
    }

    private function getHTML($url)
    {
        return cache()->remember("post-scraper-html-$url", 60*60, function() use ($url) {
            if ($this->sleep) {
                sleep($this->sleep);
            }

            return Http::get($url)->body();
        });
    }

    private function querySelector($html, $selector, $context=null)
    {
        if ($html instanceof \DOMElement) {
            $innerHTML = "";

            foreach ($html->childNodes as $child) {
                $innerHTML .= $html->ownerDocument->saveHTML($child);
            }

            $html = $innerHTML;
        } else {
            // remove inner html tag to prevent query selectors error
            if (substr_count($html, '</html>') > 1) {
                $start = strposX($html, '<html', 2);
                $end = strpos($html, '</html>') + 7;
                $html = substr($html, 0, $start) . substr($html, $end);
            }
        }

        libxml_use_internal_errors(true);
        $dom = new \DomDocument;
        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $tr = new Css2XPathService($selector);
        $tr = $tr->asXPath();
        $nodeList = $xpath->query($tr);

        return $nodeList;
    }

    private function ensureSchema($url)
    {
        if (str_contains($url, 'http')) {
            return $url;
        }

        if (str_starts_with($url, '//')) {
            return "http:$url";
        }

        $baseUrl = parse_url($this->url);

        if (str_contains($url, '//' . $baseUrl['host'])) {
            return $baseUrl['scheme'] . ':' . $url;
        }

        return $baseUrl['scheme'] . '://' . $baseUrl['host'] . $url;
    }

    private function enough()
    {
        return $this->limitResult && $this->limitResult <= count($this->result);
    }

    private function log(string $text, $data=[])
    {
        if (!$this->debug) {
            return;
        }

        $toLog = str_replace(["\r\n", "\r", "\n"], ' ', $text);

        if ($data) {
            $toLog .= (': ' . json_encode($data));
        }

        if (isset($this->logUsingClosure)) {
            $function = $this->logUsingClosure;
            $function($toLog);
        } else {
            \Log::channel('scraping')->info($toLog);
        }

    }
}
