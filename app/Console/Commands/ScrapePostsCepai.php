<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsCepai extends Command
{
    use ScrapePosts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-cepai
                            {--I|ignore-cache : Ignore cached scraped data. }
                            {--D|scraper-debug : Enable scraper logs}
                            {--C|cache-file=storage/scraper_jsons/cepai.json : Path to cache file. }
                            {--U|user=aires@cepai.com : User id or email to which imported posts will be attached. }
                            {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
                            {--L|import-limit=0 : Limit the amount of successfully imported posts. }
                            {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from cepai.com.cn';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setOptions();
        $this->porocess();
    }

    public function scrapePosts()
    {
        $result = [];
        $baseUrls = [
            'http://en.cepai.com.cn/product/instrument/mperatu/index.html',
            'http://en.cepai.com.cn/product/alves/uidin/',
            'http://en.cepai.com.cn/product/tuat/uas/',
            'http://en.cepai.com.cn/product/ccessor/ositioner/',
        ];
        foreach ($baseUrls as $url) {
            $tmp = \App\Services\PostScraperService::make($url)
                ->pagination('#main_product_center #main1_box #main1_box2 a')
                ->post('#main_product_center #main1_box #main1_box1_1')
                ->postLink('.main1_box1_12 a')
                ->value('title', '#main_product #main2_btleft span')
                ->value('description', '#main_product #main2_cpmsbj p')
                ->value('image', '#main_product #main2_cpxqbj img', 'src')
                ->value('body', '#main_product #main2_lrxq #main2_box4', 'html')
                ->value('breadcrumbs', '#main_product #main1_wei a', null, true)
                ->shot(
                    'tables-img', 
                    '#main_product #main2_lrxq #main2_box4 table',
                    false, 
                    '#main2_box4{height:3000px;} #footer{display:none}'
                )
                ->limit($this->scrapeLimit)
                ->sleep($this->sleep)
                ->debug($this->scraperDebug)
                ->scrape();
            $result = array_merge($result, $tmp);
        }

        return $result;
    }

    private function parseTitle($scrapedPost)
    {
        $title = $scrapedPost['title'];
        $title = strip_tags($title);

        return $title;
    }

    private function parseDescription($scrapedPost)
    {
        $description = $scrapedPost['description'] . "\r\n\r\n" . $scrapedPost['body'];
        $description = str_replace('</p>', "\r\n</p>", $description);
        $description = $this->descriptionEscape($description);
        $description = str_replace('&atilde;&#128;&#129;', ', ', $description);
        $description = str_replace('&iuml;&frac14;&#140;', ', ', $description);
        $description = str_replace('&iuml;&frac14;&#136;', ' (', $description);
        $description = str_replace('&iuml;&frac14;&#137;', ') ', $description);
        $description = str_replace('&acirc;&#128;&#157;', '" ', $description);
        $description = str_replace('&iuml;&frac14;&#155;', '; ', $description);
        $description = str_replace('&iuml;&frac14;&#141;', '-', $description);
        $description = str_replace('&iuml;&frac14;&#139;', '+', $description);
        $description = str_replace('&atilde;&#128;&#130;', '. ', $description);
        $description = str_replace('&iuml;&frac14;&#154;', ': ', $description);
        $description = str_replace('&iuml;&frac12;&#158;', '~', $description);
        $description = str_replace('&acirc;&#132;&#131;', 'C', $description);
        $description = str_replace('&iuml;&frac14;&#156;', '<', $description);
        $description = str_replace('&iuml;&#129;&not;', '', $description);

        return $description;
    }

    private function parseImages($scrapedPost)
    {
        return [$scrapedPost['image']??false];
    }

    private function parseSavedImages($scrapedPost)
    {
        return $scrapedPost['tables-img'];
    }

    private function parseCategory($scrapedPost)
    {
        if (in_array('CEPAI Valves', $scrapedPost['breadcrumbs'])) {
            $categorySlug = 'flowline-products';
        } else {
            $categorySlug = 'other-measurement-equipment';
        }
        return Category::getBySlug($categorySlug);
    }
}
