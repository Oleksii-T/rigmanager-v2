<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsGoldenMan extends Command
{
    use ScrapePosts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-goldenman
                            {--I|ignore-cache : Ignore cached scraped data. }
                            {--D|scraper-debug : Enable scraper logs}
                            {--C|cache-file=storage/scraper_jsons/goldenman.json : Path to cache file. }
                            {--U|user=info@goldenman.com : User id or email to which imported posts will be attached. }
                            {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
                            {--L|import-limit=0 : Limit the amount of successfully imported posts. }
                            {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from goldenman.com';

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
        $result = \App\Services\PostScraperService::make('https://goldenman.com/products/')
            ->post('.elementor-hidden-phone .products.elementor-grid li')
            ->postLink('.ast-loop-product__link')
            ->pagination('.woocommerce-pagination a')
            ->value('breadcrumbs', '.woocommerce-breadcrumb a', null, true)
            ->value('title', '.product_title')
            ->value('images', '.woocommerce-product-gallery__wrapper a img', 'src', true)
            ->value('description-short', '.woocommerce-product-details__short-description', null, false, false, false)
            ->value('description', '.woocommerce-Tabs-panel--description', 'html')
            ->shot('tables-img', '.woocommerce-Tabs-panel--description table')
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
        $desc = $scrapedPost['description-short'] . "\r\n" . $scrapedPost['description'];
        $desc = $this->descriptionEscape($desc);
        $desc = str_replace('&deg;', "°", $desc);
        $desc = str_replace('&amp;', "&", $desc);

        return $desc;
    }

    private function parseImages($scrapedPost)
    {
        $images = $scrapedPost['images'];

        return $images;
    }

    private function parseSavedImages($scrapedPost)
    {
        return $scrapedPost['tables-img'];
    }

    private function parseCategory($scrapedPost)
    {
        $map = [
            // Mud Pump
            'Mud Pump Parts' => 'mud-pump-spare-parts',
            'Mud Pump' => 'mud-pump-spare-parts',

            // Wellhead Tools
            'Manual Tongs' => 'manual-tong',
            'Power Tong' => 'power-tong',
            'Slips' => 'slip',
            'Elevator' => 'drilling-elevator',
            'Kelly spinner' => 'rig-accessories',
            'Links' => 'elevator-link',
            'Insert slip' => 'slip',
            'Spiders' => 'spider',
            'Wellhead Tools' => 'handling-tools',

            // Wellhead equipment
            'Pup Joint And Swivel Joint' => 'wellhead-equipment',
            'Christmas tree' => 'christmas-tree',
            'Casing head' => 'casing-head',
            '6A valve series' => 'wellhead-equipment',
            'API 6D gate valve' => 'gate-valve',
            'Spacer' => 'spacer-spool',

            // Rig components
            'Hydraulic power units' => 'hydraulic-power-unit',
            'Master Bushing' => 'rig-accessories',
            'Rotary Table' => 'rotary-table',
            'Winches' => 'hydraulic-winch',
            'Traveling Hook Block' => 'hook-block',

            // Well control Equipments
            'BOP-blowout preventer' => 'well-control-equipment',
            'Control system for surface mounted BOP' => 'bop-control-unit',
            'Hose' => 'lines',
            'Choke Manifold & Kill Manifold' => 'choke-manifold-kill-manifold',
            'Well control Equipments' => 'well-control-equipment',

            // Pipes and rods
            'Sucker rod' => 'sucker-rod-tools',
            'Coupling' => 'coupling',
            'Welded steel pipe' => 'drill-string',
            'float shoe and float collar' => 'float-collar-float-shoe',
            'Seamless steel pipe' => 'drill-string',
            'Casing pipe' => 'casing-pipe',
            'Tubing pipe' => 'tubing',

            // Drilling Tools
            'Drill Pipe' => 'drill-pipe-dp',
            'HWDP' => 'heavy-weight-drill-pipehwdp',
            'Drill Collar' => 'drill-collar-dc',
            'Downhole motor' => 'down-hole-motors',

            // Downhole tools
            'Stabilizer' => 'stabilizer',
            'Workover tools' => 'downhole-tools',
            'Washover cutter' => 'downhole-tools',
            'Drilling tools' => 'drill-bit',
            'Downhole tools' => 'downhole-tools',

            'drilling rig' => 'rig-accessories',
        ];

        $b = $scrapedPost['breadcrumbs'];
        $c = $b[count($b)-1];
        $mySlug = $map[$c] ?? 'others-spare-parts';

        return Category::getBySlug($mySlug);
    }
}
