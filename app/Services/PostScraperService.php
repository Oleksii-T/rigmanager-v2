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
    private array $meta = [
        'parsed_posts' => [],
        'parsed_pages' => [],
        'parsed_pages_total' => 0,
        'parsed_posts_count' => 0
    ];

    public function __construct($url)
    {
        $this->url = $url;
    }

    public static function make($url)
    {
        return new self($url);
    }

    public function abortOnEmpty(bool $abort)
    {
        $this->abortOnEmpty = $abort;

        return $this;
    }

    public function abortOnPageError(bool $abort)
    {
        $this->abortOnPageError = $abort;

        return $this;
    }

    public function debug(bool $debug)
    {
        $this->debug = $debug;

        return $this;
    }

    public function limit(int $limit)
    {
        $this->limitResult = $limit;

        return $this;
    }

    public function sleep(int $sleep)
    {
        $this->sleep = $sleep;

        return $this;
    }

    public function pagination($selector)
    {
        $this->paginationSelector = $selector;

        return $this;
    }

    public function post($selector)
    {
        $this->postSelector = $selector;

        return $this;
    }

    public function postLink($selector, $attr='href')
    {
        $this->postLinkSelector = $selector;
        $this->postLinkAttribute = $attr;

        return $this;
    }

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

    public function scrape($includeMeta=false)
    {
        $this->scrapeHelper($this->url);
        $this->sumarizeMeta();

        return $includeMeta ? ['data' => $this->result, 'meta' => $this->meta] : $this->result;
    }

    public function count()
    {
        $this->onlyCount = true;
        $this->scrapeHelper($this->url);

        return [
            'posts' => count($this->meta['parsed_posts']),
            'pages' => count($this->meta['parsed_pages'])
        ];
    }

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
     * Recursive function for page scraping
     *
     */
    public function scrapeHelper($url)
    {
        $this->log("scrapeHelper $url");
        $this->meta['parsed_pages'][$url] = [
            'start' => microtime(true)
        ];
        $html = $this->getHTML($url);
        $this->log($html);
        $paginationUrls = $this->getLinksFromUrl($html, $this->paginationSelector);
        $postsNodeLists = $this->querySelector($html, $this->postSelector);

        // scrape posts
        foreach ($postsNodeLists as $i => $postNode) {
            if ($this->onlyCount) {
                $this->meta['parsed_posts'][] = 1;
                continue;
            }
            $postUrl = $this->querySelector($postNode, $this->postLinkSelector)->item(0)->getAttribute($this->postLinkAttribute)??null;

            $this->log("  Post #$i process: $postUrl");

            if (!$postUrl) {
                $this->log("    NOT URL");
                if ($this->abortOnPageError) {
                    throw new Exception("Post #" . $i+1 . " url at '$url' can not be retrived", 1);
                }
                continue;
            }

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
            return null;
        }

        // scrape next page
        return $this->scrapeHelper($nextPageUrl);
    }

    private function processPost($url)
    {
        $this->log("    Post process start $url");

        $html = $this->getHTML($url);
        $this->log("    $html");
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

        dump($toLog);
    }
}
