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
        $result = \App\Services\PostScraperService::make('https://www.heavyoilfieldtrucks.com/listings/')
            ->post('#phContentBody_resultsSeller_gridAdResults tr')
            ->postLink('#phContentBody_resultsSeller_gridAdResults_tdTitleCell_0 a')
            ->value('title', '.equip-lbl-details h1')
            ->value('images', '#equipmentgallery img', 'src', true)
            ->value('short_specs', '.equip-lbl-details label', null, true)
            ->value('description', '.description', 'html')
            ->shot('details_tables', '.auto-listings-Tabs-panel--details')
            ->shot('specifications_tables', '.auto-listings-Tabs-panel--specifications')
            ->limit($this->scrapeLimit)
            ->sleep($this->sleep)
            ->debug($this->scraperDebug)
            ->scrape();

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
        $images = $scrapedPost['images']??[];

        return $images;
    }

    private function parseCategory($scrapedPost)
    {
        return Category::getBySlug('heavy-machinery-supply-rental');
    }

    private function parseSavedImages($scrapedPost)
    {
        $images = array_merge(
            $scrapedPost['details_tables'],
            $scrapedPost['specifications_tables'],
        );

        return $images;
    }
}