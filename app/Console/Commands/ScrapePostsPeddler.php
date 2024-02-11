<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsPeddler extends Command
{
    use ScrapePosts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-peddler
                            {--I|ignore-cache : Ignore cached scraped data. }
                            {--D|scraper-debug : Enable scraper logs}
                            {--C|cache-file=storage/scraper_jsons/peddler.json : Path to cache file. }
                            {--U|user=peddlerconsignment@sasktel.net : User id or email to which imported posts will be attached. }
                            {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
                            {--L|import-limit=0 : Limit the amount of successfully imported posts. }
                            {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from www.heavyoilfieldtrucks.com';

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
            ->post('.auto-listings-items .auto-listing')
            ->postLink('.summary .title a')
            ->value('title', '.listing .title')
            ->value('images', '#image-gallery img', 'src', true)
            ->value('price', '.price h4')
            ->value('condition', '.price .condition')
            ->value('short_specs', '.at-a-glance li', null, true)
            ->value('description', '.description', 'html')
            ->shot('details_tables', '.auto-listings-Tabs-panel--details')
            ->shot(
                'specifications_tables', 
                '.auto-listings-Tabs-panel--specifications',    
                false,
                '.auto-listings-Tabs-panel--specifications{display:block !important;}'
            )
            ->shot('few_details', '.sidebar .at-a-glance')
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

    private function addCosts($post, $scrapedPost)
    {
        try {
            $price = $scrapedPost['price'];
            $price = strtolower($price);
            $cost = [
                'currency' => 'usd',
            ];

            if (str_contains($price, 'cad') || str_contains($price, 'canadian dollar')) {
                $cost['currency'] = 'cad';
            }

            $cost['cost'] = preg_replace('/[^0-9]/', '', $price);;

            $post->saveCosts($cost);
        } catch (\Throwable $th) {
            $this->log("Can not parse price for $post->id : " . $th->getMessage(), $scrapedPost);
        }
    }

    private function parseCondition($scrapedPost)
    {
        $condition = 'new';

        if ($scrapedPost['condition'] == 'Used Vehicle') {
            $condition = 'used';
        }

        return $condition;
    }

    private function parseCountry($scrapedPost)
    {
        return 'us';
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
            $scrapedPost['few_details']
        );

        return $images;
    }
}
