<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Attachment;
use App\Jobs\PostTranslate;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Jobs\ProcessPostImages;
use Illuminate\Console\Command;
use App\Services\TranslationService;
use App\Services\ProcessImageService;
use Illuminate\Support\Facades\Storage;

class ScrapePostsCepai extends Command
{
    private $user = null;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:scrape-cepai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts from cepai.com.cn';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->user = User::where('email', 'aires@cepai.com')->first();
        $jsonFilePath = storage_path('scraper_jsons/cepai.json');

        if (file_exists($jsonFilePath)) {
            $json = file_get_contents($jsonFilePath);
            $result = json_decode($json, true);
        } else {
            $result = [];
            $baseUrls = [
                'http://en.cepai.com.cn/product/instrument/mperatu/index.html',
                'http://en.cepai.com.cn/product/alves/uidin/',
                'http://en.cepai.com.cn/product/tuat/uas/',
                'http://en.cepai.com.cn/product/ccessor/ositioner/',
            ];
            foreach ($baseUrls as $url) {
                $tmp = \App\Services\PostScraperService::make($url)
                    ->pagination('#main_product_center #main1_box #main1_box2 a')
                    ->post('#main_product_center #main1_box #main1_box1_1')
                    ->postLink('.main1_box1_12 a')
                    ->value('title', '#main_product #main2_btleft span')
                    ->value('description', '#main_product #main2_cpmsbj p')
                    ->value('image', '#main_product #main2_cpxqbj img', 'src')
                    ->value('body', '#main_product #main2_lrxq #main2_box4', 'html')
                    ->value('breadcrumbs', '#main_product #main1_wei a', null, true)
                    ->abortOnEmpty(true)
                    ->debug(true)
                    ->scrape();
                $result = array_merge($result, $tmp);
            }

            $json = json_encode($result);
            $fp = fopen($jsonFilePath, 'w');
            fwrite($fp, $json);
            fclose($fp);
        }

        $this->makePost($result);
    }

    private function makePost($scrapedData)
    {
        foreach ($scrapedData as $url => $scrapedPost) {
            if ($this->checkExist($url, $scrapedPost['title'])) {
                continue;
            }

            if (in_array('CEPAI Valves', $scrapedPost['breadcrumbs'])) {
                $categorySlug = 'flowline-products';
            } else {
                $categorySlug = 'other-measurement-equipment';
            }
            $category = Category::getBySlug($categorySlug);

            $post = [
                'user_id' => $this->user->id,
                'status' => 'pending',
                'duration' => 'unlim',
                'is_active' => true,
                'origin_lang' => 'en',
                'category_id' => $category->id,
                'type' => 'sell',
                'condition' => 'new',
                'country' => 'cn',
                'is_tba' => true,
                'scraped_url' => $url,
                // 'amount' => '',
                // 'manufacturer' => '',
                // 'manufactureDate' => '',
                // 'partNumber' => '',
            ];

            $post = Post::create($post);

            $this->addImage($post, $scrapedPost['image']??false);
            $this->addTranslations($post, $scrapedPost['title'], $scrapedPost['description']);
        }
    }

    /**
     * Detec already scraped or dublicated post
     *
     */
    private function checkExist($url, $title)
    {
        $exists = Post::where('scraped_url', $url)->count();

        if ($exists) {
            $this->info("$url - EXISTS by url");
            return true;
        }

        $exists = Translation::query()
            ->where('field', 'title')
            ->where('locale', 'en')
            ->where('translatable_type', Post::class)
            ->where('value', $title)
            ->get();

        if ($exists->isEmpty()) {
            return false;
        }

        foreach ($exists as $e) {
            $post = $e->translatable;
            $this->log(" found '$title' in $e->id");
            if ($post->user_id == $this->user->id) {
                $this->info("$url - EXISTS by title '$title' in post #$post->id");
                return true;
            }
        }

        return false;
    }

    private function addImage($post, $url)
    {
        if (!$url) {
            return;
        }
        $disk = Storage::disk('aimages');
        try {
            $contents = file_get_contents($url);
        } catch (\Throwable $th) {
            $this->log(" Can not download image from $url. " . $th->getMessage());
            return;
        }
        $ext = ProcessImageService::mimeFromUrl($url);

        if (!$ext) {
            $this->log(" Can not download image from $url. Can not autodetermine extension");
            return;
        }

        $name = substr($url, strrpos($url, '/') + 1);

        if (strrpos($name, '.') === false) {
            $name .= ".$ext";
        }

        $random_name = Str::random(40) . ".$ext";

        $disk->put($random_name, $contents);

        $size = $disk->size($random_name);
        $mime = $disk->mimeType($random_name);

        if (!in_array($mime, ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'])) {
            $disk->delete($random_name);
            $this->log(" Can not download image from $url. Invalid extension");
            return;
        }

        $attachment = Attachment::create([
            'attachmentable_id' => $post->id,
            'attachmentable_type' => Post::class,
            'name' => $random_name,
            'original_name' => $name,
            'group' => 'images',
            'type' => 'image',
            'size' => $size
        ]);


        ProcessPostImages::dispatch([$attachment]);
    }

    private function addTranslations($post, $title, $description)
    {
        $textLocale = (new TranslationService())->detectLanguage("$title. $description");

        $post->saveTranslations([
            'slug' => [
                $textLocale => makeSlug($title, Post::allSlugs())
            ],
            'title' => [
                $textLocale => $title
            ],
            'description' => [
                $textLocale => $description
            ]
        ]);

        PostTranslate::dispatch($post);

        if ($textLocale != 'en') {
            $post->update([
                'origin_lang' => $textLocale
            ]);
        }
    }

    private function log(string $text, $data=[])
    {
        $toLog = $text;

        if ($data) {
            $toLog .= (': ' . json_encode($data));
        }

        \Log::channel('scraping')->info($toLog);

    }
}
