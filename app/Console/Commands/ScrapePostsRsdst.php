<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsRsdst extends Command
{
    use ScrapePosts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-rsdst
                            {--I|ignore-cache : Ignore cached scraped data. }
                            {--D|scraper-debug : Enable scraper logs}
                            {--C|cache-file=storage/scraper_jsons/rsdst.json : Path to cache file. }
                            {--U|user=rsdxs@pyzyrsd.com : User id or email to which imported posts will be attached. }
                            {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
                            {--L|import-limit=0 : Limit the amount of successfully imported posts. }
                            {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from en.rsdst.com';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setOptions();
        foreach ($this->user->posts as $p) {
            $p->delete();
        }
        $this->porocess();
    }

    private function scrapePosts()
    {
        $result = [];
        $baseUrls = [
            'https://en.rsdst.com/products/zjkk/',
            'https://en.rsdst.com/products/hyfqw/',
            'https://en.rsdst.com/products/sjfqw/',
            'https://en.rsdst.com/products/gcns/',
            'https://en.rsdst.com/products/ksxx/',
            'https://en.rsdst.com/products/wscl/',
        ];
        foreach ($baseUrls as $url) {
            $tmp = \App\Services\PostScraperService::make($url)
                ->post('.list-style1 .list-item')
                ->postLink('.list-info .list-tit a')
                ->pagination('.u-paging a')
                ->value('breadcrumbs', '.wsc-breadCon a', null, true)
                ->value('title', '.pd-info .d-tit')
                ->value('images', '.carousel-inner img', 'src', true)
                ->value('description-short', '.pd-info .pd-intro')
                ->value('description', '.content-main .wsc-edit', 'html', true)
                ->value('description-images', '.content-main img', 'src', true)
                ->abortOnPageError(false) // pagination has fail value
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
        $descriptions = [$scrapedPost['description-short']];
        foreach ($scrapedPost['description'] as $d) {
            if (str_contains($d, 'RSD strength')) {
                continue;
            }
            $descriptions[] = $d;
        }
        $desc = implode("\r\n", $descriptions);
        $desc = str_replace('Technical parameters', '', $desc);
        $desc = $this->descriptionEscape($desc);

        return $desc;
    }

    private function parseImages($scrapedPost)
    {
        $images = $scrapedPost['images'];
        $images = array_merge($images, $scrapedPost['description-images']);

        return $images;
    }

    private function parseCategory($scrapedPost)
    {
        $mySlug = 'solid-control-equipment';

        return Category::getBySlug($mySlug);
    }
}
