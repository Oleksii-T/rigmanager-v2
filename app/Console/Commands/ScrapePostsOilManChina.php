<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Traits\ScrapePosts;
use Illuminate\Console\Command;

class ScrapePostsOilManChina extends Command
{
    use ScrapePosts;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-oilmanchina
                            {--I|ignore-cache : Ignore cached scraped data. }
                            {--D|scraper-debug : Enable scraper logs}
                            {--C|cache-file=storage/scraper_jsons/oilmanchina.json : Path to cache file. }
                            {--U|user=christal@oilmancn.com : User id or email to which imported posts will be attached. }
                            {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
                            {--L|import-limit=0 : Limit the amount of successfully imported posts. }
                            {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from www.oilmanchina.com';

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
        $result = \App\Services\PostScraperService::make('https://www.oilmanchina.com/products.html')
            ->post('.pic-list .item')
            ->postLink('.title-content a')
            ->pagination('.cmsmasters_wrap_pagination a')
            ->value('title', '.cont_r  h2')
            ->value('images', '#slidePic img', 'src', true)
            ->value('category', '.aside-list .on a', null, true)
            ->value('description', '#product_description', 'html')
            ->value('description-images', '#product_description img', 'src', true)
            ->value('details1-keys', '.cont_r .p_name', null, true)
            ->value('details1-values', '.cont_r .p_attribute', null, true)
            ->value('details2-keys', '#detail_infomation th', null, true)
            ->value('details2-values', '#detail_infomation td', null, true)
            ->shot('tables-img', '#product_description table')
            ->limit($this->scrapeLimit)
            ->sleep($this->sleep)
            ->debug($this->scraperDebug)
            ->scrape();

        return $result;
    }

    private function validateScrapedPost($scrapedPost)
    {
        $c = $scrapedPost['category'][0];
        $c = preg_replace('/[()\d]/', '', $c);
        $skipCategories = [
            'Replacement Auger Teeth',
            'Wall Plastering Spray Machine'
        ];

        if (in_array($c, $skipCategories)) {
            return false;
        }

        return true;
    }

    private function parseTitle($scrapedPost)
    {
        $title = $scrapedPost['title'];
        $title = strip_tags($title);

        return $title;
    }

    private function parseDescription($scrapedPost)
    {
        $desc = $scrapedPost['description'];
        $desc = str_replace('Product Description', '', $desc);
        $desc = str_replace('SPECIFICATION AND TECHNICAL DATA:', '', $desc);
        $desc = str_replace('Technical Specifications', '', $desc);
        $desc = str_replace('&acirc;&#128;&cent;', '- ', $desc);
        $desc = $this->descriptionEscape($desc);

        $cutFooters = [
            'If any needs',
            'Visit our website',
            'Any enquiry please'
        ];

        foreach ($cutFooters as $cutFooter) {
            $footerPost = strpos($desc, $cutFooter);
            if ($footerPost !== false) {
                $desc = substr($desc, 0, $footerPost);
            }
        }

        $excludeKey = [
            'Product Name:',
            'Price:',
        ];

        $detailsText = '';
        foreach ($scrapedPost['details1-keys'] as $i => $detailKey) {
            if (in_array($detailKey, $excludeKey)) {
                continue;
            }
            $detailsText .= $detailKey . ' ' . $scrapedPost['details1-values'][$i] . "\r\n";
        }
        foreach ($scrapedPost['details2-keys'] as $i => $detailKey) {
            if (in_array($detailKey, $excludeKey)) {
                continue;
            }
            $detailsText .= $detailKey . ' ' . $scrapedPost['details2-values'][$i+1] . "\r\n";
        }

        if ($detailsText) {
            $desc = $detailsText . "\r\n" . $desc;
        }

        return $desc;
    }

    private function parseImages($scrapedPost)
    {
        // may generate dublicated images
        $images = $scrapedPost['images']??false;
        $images = array_merge($images, $scrapedPost['description-images']??[]);

        return $images;
    }

    private function parseSavedImages($scrapedPost)
    {
        return $scrapedPost['tables-img'];
    }

    private function addCosts($post, $scrapedPost)
    {
        try {
            $price = null;
            foreach ($scrapedPost['details1-keys'] as $i => $detailKey) {
                if ($detailKey == 'Price:') {
                    $price = $scrapedPost['details1-values'][$i];
                }
            }

            if (!$price) {
                return;
            }

            $price = str_replace(' ', '', $price);
            $price = strtolower($price);
            $price = explode('/', $price);
            $postUpdateData = [
                'cost_per' => $price[1]
            ];
            $price = $price[0];
            preg_match('/[a-z]*/', $price, $curr);
            $cost = [
                'currency' => $curr[0],
            ];
            $price = preg_replace('/[a-z]*/', '', $price);

            if (str_contains($price, '-')) {
                $price = explode('-', $price);
                $from = $price[0];
                $to = $price[1];
                $cost['cost_from'] = $from;
                $cost['cost_to'] = $to;
                $cost['is_double_cost'] = true;
                $postUpdateData['is_double_cost'] = true;
            } else {
                $cost['cost'] = $price;
            }

            $post->saveCosts($cost);
            $post->update($postUpdateData);

        } catch (\Throwable $th) {
            $this->log("Can not parse price for $post->id : " . $th->getMessage(), $scrapedPost);
        }
    }

    private function parseCategory($scrapedPost)
    {
        $map = [
            'Oilfield Production Equipment' => 'production-equipment-octg',
            'Oilfield Cementing Tools' => 'downhole-tools',
            'Oilfield Downhole Tools' => 'downhole-tools',
            'Top Drive Spare Parts' => 'top-drive-drilling-system',
            'Wellhead Assembly' => 'wellhead-equipment',
            'Mud Pump Spare Parts' => 'mud-pump-spare-parts',
            'Drilling Handling Tools' => 'handling-tools',
            'Solids Control Equipment' => 'solid-control-equipment',
            'Hydraulic Power Tongs' => 'power-tong',
            'Drill String Components' => 'drill-string',
            'Hydraulic Submersible Water Pump' => 'electric-submersible-pump',
            'Bop Well Control Equipment' => 'well-control-equipment',
            'Oil And Gas Pipes' => 'drill-pipe-dp',
            'Bucking Unit' => 'bucking-unit',
            'Dth Hammer Drill Bits' => 'drill-bit',
            'Drilling Rig Accessories' => 'rig-accessories',
            'Elevated Fishing And Milling Tools' => 'fishing-tools',
        ];

        $c = $scrapedPost['category'][0];
        $c = preg_replace('/[()\d]/', '', $c);
        $mySlug = $map[$c] ?? 'other-measurement-equipment';

        return Category::getBySlug($mySlug);
    }
}
