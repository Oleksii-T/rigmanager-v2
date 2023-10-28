<?php

namespace App\Console\Commands;

use App\Models\User;
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
    protected $signature = 'posts:scrape-cepai';

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
        $this->cacheFile = storage_path('scraper_jsons/cepai.json');
        $this->user = User::where('email', 'aires@cepai.com')->first();

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
        $description = strip_tags($description);

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
