<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsDtoSupply extends Command
{
    use ScrapePosts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-dtosupply
                            {--I|ignore-cache : Ignore cached scraped data. }
                            {--D|scraper-debug : Enable scraper logs}
                            {--C|cache-file=storage/scraper_jsons/dtosupply.json : Path to cache file. }
                            {--U|user=sales@dtosupply.com : User id or email to which imported posts will be attached. }
                            {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
                            {--L|import-limit=0 : Limit the amount of successfully imported posts. }
                            {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from dtosupply.com';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setOptions();
        $this->porocess();
    }

    //public function value(string $name, string $selector, string $attribute=null, bool $isMultiple=false, bool $getFromPostsPage=false, bool $required=true)
    private function scrapePosts()
    {
        $result = \App\Services\PostScraperService::make('https://dtosupply.com/shop/')
            ->pagination('.page-numbers a')
            ->post('.products .product')
            ->postLink('.woocommerce-LoopProduct-link')
            ->value('title', '.product_title')
            ->value('images', '.images .wp-post-image', 'src', true, false, false)
            ->value('price', '.price', null, false, false, false)
            ->value('details', '.woocommerce-product-details__short-description', null, false, false, false)
            ->value('posted_in', '.posted_in')
            ->value('tagged_as', '.tagged_as', null, false, false, false)
            ->value('description', '.woocommerce-Tabs-panel--description', 'html', false, false, false)
            ->value('breadcrumb', '.woocommerce-breadcrumb a', null, true)
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
        $desc = str_replace('Description', "", $description);
        $desc = str_replace('sales@dtosupply.com', "", $description);
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
        $title = strtolower($scrapedPost['title']) . strtolower($scrapedPost['details']);

        if (str_contains($title, 'used')) {
            $condition = 'used';
        } else if (str_contains($title, 'recertified') || str_contains($title, 'refurbished')) {
            $condition = 'refurbished';
        }

        return $condition;
    }

    private function parseCountry($scrapedPost)
    {
        return 'us';
    }

    private function parseCategory($scrapedPost)
    {
        return Category::getBySlug('top-drive-drilling-system');
    }
}
