<?php

namespace App\Console\Commands;

use App\Models\User;
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
                            {--ignore-cache : Ignore cached scraped data }';

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
        $this->cacheFile = storage_path('scraper_jsons/lakepetro.json');
        $this->user = User::where('email', 'sales@lakepetro.com')->first();
        $this->ignoreCache = $this->option('ignore-cache');

        $this->porocess();
    }

    private function scrapePosts()
    {
        $result = [];
        $baseUrls = [
            'https://www.lakepetro.com/rig-accessories' => '',
            'https://www.lakepetro.com/well-control-equipment' => '',
            'https://www.lakepetro.com/solid-control-equipment' => '',
            'https://www.lakepetro.com/drill-string' => '',
            'https://www.lakepetro.com/handling-tools' => '',
            'https://www.lakepetro.com/downhole-tools' => '',
            'https://www.lakepetro.com/mud-pump-parts' => '',
            'https://www.lakepetro.com/production-equipment-octg' => '',
            'https://www.lakepetro.com/wellhead-equipment' => '',
            'https://www.lakepetro.com/flowline-products' => '',
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
                ->value('category', '.bannerBox .bannerSubheading')
                ->staticValue('category', $categSlug)
                ->abortOnPageError(false) // some lakepost posts has empty href
                ->abortOnEmptyValue(false) // some lakepost posts returns 404 page or doesnt have description
                ->debug(1)
                ->scrape();
            $result = array_merge($result, $tmp);
        }

        return $result;
    }

    private function validateScrapedPost($url, $scrapedPost)
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
            // Remove <table> from specs to get only description.
            $specs = $scrapedPost['tab-1-html'];
            $startTable = strpos($specs, '<table');
            $endTable = strpos($specs, '</table>');
            $description = substr($specs, 0, $startTable) . substr($specs, $endTable+8);
        }

        $description = strip_tags($description);
        $description = str_replace('&Acirc;', '', $description);
        $description = str_replace('&nbsp;', '', $description);
        $description = preg_replace('/(\r\n){3,}/', "\r\n\r\n", $description);

        return $description;
    }

    private function parseImages($scrapedPost)
    {
        // may generate dublicated images
        $images = [$scrapedPost['images']??false];
        $images = array_merge($images, $scrapedPost['tab-1-images']??[]);
        $images = array_merge($images, $scrapedPost['tab-2-images']??[]);
        $images = array_merge($images, $scrapedPost['tab-3-images']??[]);

        return $images;
    }

    private function parseCategory($scrapedPost)
    {
        $map = [
            'Pump Jack/Pumping Unit' => 'production-equipment-octg',
            'Sucker Rod Pump' => 'sucker-rod-pump',
            'Sucker Rods' => 'sucker-rod',
            'seamless pipes' => 'tubing',
            'Drilling and milling' => 'drill-string',
            'downhole tools' => 'downhole-tools',
            'Wellhead and Christmas Tree' => 'wellhead-equipment',
            'API Valves' => 'flowline-products',
            'Manifold' => 'choke-manifold-kill-manifold',
            'BOP' => 'well-control-equipment',
            'Wireline Slickline' => 'fishing-tools',
            'Solids Control Equipment' => 'solid-control-equipment',
            'MWD and LWD' => '',
            'ESP / ESPCP' => '',
            'Coiled Tubing Tools' => '',
            'Artificial Lifting' => '',
        ];

        $mySlug = $map[$scrapedPost['category']] ?? 'other-measurement-equipment';

        return Category::getBySlug($mySlug);
    }
}
