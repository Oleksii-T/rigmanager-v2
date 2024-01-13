<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsLakePetro extends Command
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
                            {--C|cache-file=storage/scraper_jsons/lakepetro.json : Path to cache file. }
                            {--U|user=sales@lakepetro.com : User id or email to which imported posts will be attached. }
                            {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
                            {--L|import-limit=0 : Limit the amount of successfully imported posts. }
                            {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from www.lakepetro.com';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setOptions();
        $this->porocess();
    }

    private function scrapePosts()
    {
        $result = [];
        $baseUrls = [
            'https://www.lakepetro.com/rig-accessories' => 'rig-accessories',
            'https://www.lakepetro.com/well-control-equipment' => 'well-control-equipment',
            'https://www.lakepetro.com/solid-control-equipment' => 'solid-control-equipment',
            'https://www.lakepetro.com/drill-string' => 'drill-string',
            'https://www.lakepetro.com/handling-tools' => 'handling-tools',
            'https://www.lakepetro.com/downhole-tools' => 'downhole-tools',
            'https://www.lakepetro.com/mud-pump-parts' => 'mud-pump-spare-parts',
            'https://www.lakepetro.com/production-equipment-octg' => 'production-equipment-octg',
            'https://www.lakepetro.com/wellhead-equipment' => 'wellhead-equipment',
            'https://www.lakepetro.com/flowline-products' => 'flowline-products',
        ];

        foreach ($baseUrls as $url => $categSlug) {
            $tmp = \App\Services\PostScraperService::make($url)
                ->post('.productSection .col-sm-6.col-md-4.paddBottom30')
                ->postLink('.productTitle a')
                ->value('title',        '.popupBox .arrowList h2')
                ->value('images',       '.productSlider .slide img', 'src', true)
                ->value('bulletlist',   '.popupBox .arrowList li',   null,  true)
                ->value('tabs',         '.tabsWrapper .tabs li',     null,  true)
                ->value('tab-1-html',   '.tabsWrapper #tab-1',       'html'     ) // tab 1 main contain "Specs" or "Drowings"
                ->value('tab-1-images', '.tabsWrapper #tab-1 img',   'src', true) // tab 1 main contain "Specs" or "Drowings"
                ->value('tab-2-html',   '.tabsWrapper #tab-2',       'html'     ) // tab 2 main contain "Description" or "Drowings"
                ->value('tab-2-images', '.tabsWrapper #tab-2 img',   'src', true) // tab 2 main contain "Description" or "Drowings"
                ->value('tab-3-html',   '.tabsWrapper #tab-3',       'html'     ) // tab 3 main contain "Drowings" or "Contact Us"
                ->value('tab-3-images', '.tabsWrapper #tab-3 img',   'src', true) // tab 3 main contain "Drowings" or "Contact Us"
                ->shot('tab-3-table-img', '.tabsWrapper #tab-3 table', false)
                ->abortOnPageError(false) // some lakepost posts has empty href
                ->nullableValues() // some lakepost posts returns 404 page so mark all value as nullable
                ->limit($this->scrapeLimit)
                ->sleep($this->sleep)
                ->debug($this->scraperDebug)
                ->scrape();

            foreach ($tmp as &$p) {
                $p['category'] = $categSlug;
            }
            $result = array_merge($result, $tmp);
        }

        return $result;
    }

    private function validateScrapedPost($scrapedPost)
    {
        if (!$scrapedPost['title']) {
            return false;
        }

        $tabs = $scrapedPost['tabs'];
        if (
            !in_array('Description', $tabs) &&
            !in_array('More Details', $tabs) &&
            !in_array('Technical Specification', $tabs) &&
            !in_array('Techanical Specification', $tabs)
        ) {
            // posts without those tabs have non-standart layout - can not import.
            return false;
        }

        return true;
    }

    private function parseTitle($scrapedPost)
    {
        $title = $scrapedPost['title'];
        $title = explode('Date', $title);
        $title = $title[0];
        $title = strip_tags($title);

        return $title;
    }

    private function parseDescription($scrapedPost)
    {
        $tabs = $scrapedPost['tabs'];
        if (in_array('Description', $tabs)) {
            // we have standart description
            $field = match (array_search('Description', $tabs)) {
                0 => 'tab-1-html',
                1 => 'tab-2-html',
                2 => 'tab-3-html',
            };
            $description = $scrapedPost[$field];
        } else if (in_array('More Details', $tabs)) {
            // we have standart description
            $field = match (array_search('More Details', $tabs)) {
                0 => 'tab-1-html',
                1 => 'tab-2-html',
                2 => 'tab-3-html',
            };
            $description = $scrapedPost[$field];
        } else {
            // take Tech specs as description.
            // TechSpecs are always in first tab.
            $description = $scrapedPost['tab-1-html'];
        }

        $description = $this->descriptionEscape($description);

        return $description;
    }

    private function parseImages($scrapedPost)
    {
        // may generate dublicated images
        $images = $scrapedPost['images']??false;
        $images = array_merge($images, $scrapedPost['tab-1-images']??[]);
        $images = array_merge($images, $scrapedPost['tab-2-images']??[]);
        $images = array_merge($images, $scrapedPost['tab-3-images']??[]);

        return $images;
    }

    private function parseCategory($scrapedPost)
    {
        return Category::getBySlug($scrapedPost['category']);
    }
}
