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
    protected $signature = 'posts:scrape-lakepetro
                            {--ignore-cache : Ignore cached scraped data. }
                            {--scraper-debug : Enable scraper logs}
                            {--C|cache-file=storage/scraper_jsons/cepai2.json : Path to cache file. }
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
        $description = $scrapedPost['description'];
        $description = $this->descriptionEscape($description);

        return $description;
    }

    private function parseImages($scrapedPost)
    {
        return [$scrapedPost['image']??false];
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
