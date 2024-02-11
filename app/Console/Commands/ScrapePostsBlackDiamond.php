<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsBlackDiamond extends Command
{
    use ScrapePosts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-blackdiamond
                            {--I|ignore-cache : Ignore cached scraped data. }
                            {--D|scraper-debug : Enable scraper logs}
                            {--C|cache-file=storage/scraper_jsons/blackdiamond.json : Path to cache file. }
                            {--U|user=info@blackdiamonddrilling.com : User id or email to which imported posts will be attached. }
                            {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
                            {--L|import-limit=0 : Limit the amount of successfully imported posts. }
                            {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from www.blackdiamonddrilling.com';

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
            'https://www.blackdiamonddrilling.com/equipment/coil-tubing-equipment' => 'coil-tubing-eq',
            'https://www.blackdiamonddrilling.com/equipment/cranes' => 'cranes',
            'https://www.blackdiamonddrilling.com/equipment/drill-bits-for-sale' => 'drill-bit',
            'https://www.blackdiamonddrilling.com/equipment/drill-collars-for-sale' => 'drill-collar-dc',
            'https://www.blackdiamonddrilling.com/equipment/drill-pipe-for-sale' => 'drill-pipe-dp',
            'https://www.blackdiamonddrilling.com/equipment/drilling-rigs-for-sale' => 'rig-accessories',
            'https://www.blackdiamonddrilling.com/equipment/handling-tools-for-sale' => 'handling-tools',
            'https://www.blackdiamonddrilling.com/equipment/loaders' => 'vehicles',
            'https://www.blackdiamonddrilling.com/equipment/miscellaneous' => 'others-spare-parts',
            'https://www.blackdiamonddrilling.com/equipment/pipe-racks-for-sale' => 'pipe-racks',
            'https://www.blackdiamonddrilling.com/equipment/pumps-for-sale' => 'mud-pump-spare-parts',
            'https://www.blackdiamonddrilling.com/equipment/rotary-tools-for-sale' => 'rotary-table',
            // 'https://www.blackdiamonddrilling.com/equipment/service-equipment-for-sale' => '',
            'https://www.blackdiamonddrilling.com/equipment/tanks-for-sale' => 'trailers',
            'https://www.blackdiamonddrilling.com/equipment/trailers-for-sale' => 'trailers',
            'https://www.blackdiamonddrilling.com/equipment/trucks-for-sale' => 'trucks',
        ];

        foreach ($baseUrls as $url => $categSlug) {
            $tmp = \App\Services\PostScraperService::make($url)
                ->post('.product-box-grid .w-dyn-item')
                ->postLink('a')
                ->value('title', '.product-page-title')
                ->value('images', '.w-slider-mask img', 'src', true, false, false)
                ->value('specs_keys', '.list-item .product-content-title', null, true, false, false)
                ->value('specs_values', '.list-item .paragraph', null, true, false, false)
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

    private function parseTitle($scrapedPost)
    {
        $title = $scrapedPost['title'];
        $title = strip_tags($title);

        return $title;
    }

    private function parseDescription($scrapedPost)
    {
        $description = [];

        foreach ($scrapedPost['specs_keys'] as $i => $name) {
            $value = $scrapedPost['specs_values'][$i];
            if (!$value) {
                continue;
            }

            $description[] = "$name: $value";
        }

        $description = implode("\r\n\r\n", $description);
        $description = $this->descriptionEscape($description);

        return $description;
    }

    private function parseImages($scrapedPost)
    {
        $images = $scrapedPost['images']??[];

        return $images;
    }

    private function parseCountry($scrapedPost)
    {
        return 'us';
    }

    private function parseCategory($scrapedPost)
    {
        return Category::getBySlug($scrapedPost['category']);
    }
}
