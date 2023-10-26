<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\Css2XPathService;

class PostScraperService
{
    private string $url = '';
    private string $paginationSelector;
    private string $postSelector;
    private string $postLinkSelector;
    private string $postLinkAttribute;
    private bool $abortOnEmpty = true;
    private bool $abortOnPageError = true;
    private bool $onlyCount = false;
    private bool $debug = false;
    private int $limitResult = 0;
    private int $sleep = 0;
    private array $values = [];
    private array $result = [];
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
    }

    public static function make(string $url)
    {
        return new self($url);
    }

    public function abortOnEmpty(bool $abort)
    {
        $this->abortOnEmpty = $abort;

        return $this;
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
        $this->limitResult = $limit;

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
     *
     * @return self
     */
    public function value(string $name, string $selector, string $attribute=null, bool $isMultiple=false, bool $getFromPostsPage=false)
    {
        $this->values[$name] = [
            'selector' => $selector,
            'is_multiple' => $isMultiple,
            'attribute' => $attribute,
            'from_posts_page' => $getFromPostsPage
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
        $this->log(' HTML: ' . str_replace(["\r\n", "\r", "\n"], ' ', $html));
        $paginationUrls = $this->paginationSelector
            ? $this->getLinksFromUrl($html, $this->paginationSelector)
            : [];
        $postsNodeLists = $this->querySelector($html, $this->postSelector);

        // scrape posts
        foreach ($postsNodeLists as $i => $postNode) {
            $postUrl = $this->querySelector($postNode, $this->postLinkSelector)->item(0)->getAttribute($this->postLinkAttribute)??null;

            $this->log("  Post #$page:$i process: $postUrl");

            if (!$postUrl) {
                $this->log("    NOT URL");
                if ($this->abortOnPageError) {
                    throw new Exception("Post #" . $i+1 . " url at '$url' can not be retrived", 1);
                }
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
                $this->log("    ENOUGHT");
                break;
            }
        }

        $this->log("  Posts process done");

        // find next page url
        foreach ($paginationUrls as $paginationUrl) {
            if (!isset($this->meta['parsed_pages'][$paginationUrl])) {
                $nextPageUrl = $paginationUrl;
                $this->log("  Next page url found: $nextPageUrl");
                break;
            }
        }

        $this->meta['parsed_pages'][$url]['end'] = microtime(true);

        // check is next page must be scraped
        if ($this->enough() || !isset($nextPageUrl)) {
            $this->log("  ENOUGHT or NO PAGE");
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

        $html = $this->getHTML($url);
        $this->log('    HTML: ' . str_replace(["\r\n", "\r", "\n"], ' ', $html));
        $values = [];

        foreach ($this->values as $key => $data) {
            $this->log("      value '$key'", $data);

            if ($data['from_posts_page']) {
                continue;
            }

            $values[$key] = $this->scrapeValue($html, $key, $data);
        }

        return $values;
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
    private function scrapeValue($html, $key, $data)
    {
        $nodeList = $this->querySelector($html, $data['selector']);

        $this->log("        selector '{$data['selector']}'");

        if ($data['is_multiple']) {
            $this->log("        is multiple");
            $res = [];
            foreach ($nodeList as $node) {
                $res[] = $this->scrapeValueHelper($node, $key, $data);
            }
            $this->log("        result: ", $res);
        } else {
            $this->log("        is not multiple");
            $node = $nodeList->item(0);
            $res = $this->scrapeValueHelper($node, $key, $data);
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
    private function scrapeValueHelper($node, $key, $data)
    {
        $attr = $data['attribute'];
        $res = '';

        if ($attr && $attr == 'html') {
            $res = $node?->ownerDocument->saveHtml($node);
            $res = $this->crearHtml($res)??null;
        } else if ($attr) {
            $res = $node->getAttribute($attr)??null;
        } else {
            $res = $node->nodeValue??null;
        }

        if (!$res && $this->abortOnEmpty) {
            throw new \Exception("Empty value encounered for '$key' value ({$data['selector']})", 1);
        }

        if ($res && $attr && in_array($attr, ['href', 'src'])) {
            $res = $this->ensureSchema($res);
        }

        return $res;
    }

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
        $html = preg_replace('/^.*<body>/s', '', $html);
        $html = preg_replace('/<\/body>.*$/s', '', $html);

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
        return cache()->remember("post-scraper-html-$url", 60*10, function() use ($url) {
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

    private function domel2html($node)
    {

    }

    private function log(string $text, $data=[])
    {
        if (!$this->debug) {
            return;
        }

        $toLog = $text;

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
