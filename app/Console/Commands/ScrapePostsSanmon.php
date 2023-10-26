<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsSanmon extends Command
{
    use ScrapePosts;

    private $user = null;
    private $skipped = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-sanmon';

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
        $this->user = User::where('email', 'sales@cnsanmon.com')->first();

        foreach ($this->user->posts as $p) {
            $p->delete();
        }

        $jsonFilePath = storage_path('scraper_jsons/sanmon.json');

        if (file_exists($jsonFilePath)) {
            $this->info("Loading scraped data from $jsonFilePath file");
            $json = file_get_contents($jsonFilePath);
            $result = json_decode($json, true);
            $this->line(" Done");
        } else {
            $this->info("Web scrappping...");
            $result = \App\Services\PostScraperService::make('http://www.cnsanmon.com/sjal/wireline-slickline/')
                ->pagination('.ny_pages a')
                ->post('.nproduct li')
                ->postLink('a')
                ->value('title', '.nmain .news_title')
                ->value('description', '.nmain .newsbody', 'html')
                ->value('thumb', 'img', 'src', false, true)
                ->value('images', '.newsbody img', 'src', true)
                ->value('category', '.nbt')
                ->abortOnEmpty(true)
                ->sleep(0)
                ->scrape();

            $json = json_encode($result);
            $fp = fopen($jsonFilePath, 'w');
            fwrite($fp, $json);
            fclose($fp);
            $this->line(" Done");
        }

        // dd($result);

        $count = count($result);
        if (!$this->confirm("Found $count posts. Proceed?")) {
            return;
        }

        $this->makePost($result);

        $this->info("Successfully processed $count posts.");
        if ($this->skipped) {
            $this->warn("Skipped: $this->skipped");
        }
        $this->newLine(1);
        $this->info('Process finished');
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
        $description = strip_tags($description);
        $description = str_replace('&Acirc;', '', $description);
        $description = str_replace('&nbsp;', '', $description);
        $description = preg_replace('~[\r\n]+~', '\r\n', $description); //! FIX

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
            'MWD and LWD' => '',
            'ESP / ESPCP' => '',
            'Coiled Tubing Tools' => '',
            'Artificial Lifting' => '',
        ];

        $mySlug = $map[$scrapedPost['category']] ?? 'other-measurement-equipment';

        return Category::getBySlug($mySlug);
    }
}
