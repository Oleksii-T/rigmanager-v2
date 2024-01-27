<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsSanmon extends Command
{
    use ScrapePosts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-sanmon
                            {--I|ignore-cache : Ignore cached scraped data. }
                            {--D|scraper-debug : Enable scraper logs}
                            {--C|cache-file=storage/scraper_jsons/sanmon.json : Path to cache file. }
                            {--U|user=sales@cnsanmon.com : User id or email to which imported posts will be attached. }
                            {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
                            {--L|import-limit=0 : Limit the amount of successfully imported posts. }
                            {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from www.cnsanmon.com';

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
        $result = \App\Services\PostScraperService::make('http://www.cnsanmon.com/sjal')
            ->pagination('.ny_pages a')
            ->post('.nproduct li')
            ->postLink('a')
            ->value('title', '.nmain .news_title')
            ->value('description', '.nmain .newsbody', 'html')
            ->value('thumb', 'img', 'src', false, true)
            ->value('images', '.newsbody img', 'src', true)
            ->value('category', '.nbt')
            ->limit($this->scrapeLimit)
            ->sleep($this->sleep)
            ->debug($this->scraperDebug)
            ->scrape();

        return $result;
    }

    private function parseTitle($scrapedPost)
    {
        // Wireline Pulling ToolsDate：【2022-05-07 11:02】 Read：【】Times
        $title = $scrapedPost['title'];
        $title = explode('Date', $title);
        $title = $title[0];
        $title = strip_tags($title);

        return $title;
    }

    private function parseDescription($scrapedPost)
    {
        $description = $scrapedPost['description'];
        $description = $this->descriptionEscape($description);
        $description = str_replace('&acirc;&#129;&#132;', '/', $description);
        $description = str_replace('&middot;', '- ', $description);
        $description = str_replace('&acirc;&#128;&#148;', '-', $description);

        return $description;
    }

    private function parseImages($scrapedPost)
    {
        $images = [$scrapedPost['thumb']??false];
        $images = array_merge($images, $scrapedPost['images']??[]);

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
            'MWD and LWD' => 'downhole-tools',
            'ESP / ESPCP' => 'mud-pump-spare-parts',
            'Coiled Tubing Tools' => 'downhole-tools',
            'Artificial Lifting' => 'solid-control-equipment',
        ];

        $mySlug = $map[$scrapedPost['category']] ?? 'other-measurement-equipment';

        return Category::getBySlug($mySlug);
    }
}
