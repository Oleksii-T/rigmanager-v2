<?php

namespace App\Traits;

use App\Models\Post;
use App\Models\Attachment;
use App\Jobs\PostTranslate;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Jobs\ProcessPostImages;
use App\Services\TranslationService;
use App\Services\ProcessImageService;
use Illuminate\Support\Facades\Storage;

trait ScrapePosts
{
    private $user = null;
    private $alreadyExisted = [];
    private $failValidation = [];
    private $cacheFile;
    private $userEmail;
    private $ignoreCache;

    public function porocess()
    {
        if (!$this->ignoreCache && file_exists($this->cacheFile)) {
            $this->info("Loading cached scraped data from $this->cacheFile file");
            $json = file_get_contents($this->cacheFile);
            $scrapedPosts = json_decode($json, true);
            $this->line(" Done");
        } else {
            $this->info("Web scrappping...");
            $scrapedPosts = $this->scrapePosts();
            $json = json_encode($scrapedPosts);
            $fp = fopen($this->cacheFile, 'w');
            fwrite($fp, $json);
            fclose($fp);
            $this->line(" Posts been cashed into $this->cacheFile file");
            $this->line(" Done");
        }

        $count = count($scrapedPosts);
        if (!$this->confirm("Found $count posts. Proceed?")) {
            return;
        }

        $this->importScrapedPosts($scrapedPosts);

        $this->info("Successfully processed $count posts.");

        if ($this->alreadyExisted) {
            $error = "Already Existed: " . count($this->alreadyExisted);
            $this->warn("$error. See log for more info.");
            $this->log($error, $this->alreadyExisted);
        }

        if ($this->failValidation) {
            $error = "Failed Validation: " . count($this->failValidation);
            $this->warn("$error. See log for more info.");
            $this->log($error, $this->failValidation);
        }

        $this->newLine(1);
        $this->info('Process finished');
    }

    private function importScrapedPosts($scrapedData)
    {
        $this->info("Importing into db...");
        $bar = $this->output->createProgressBar(count($scrapedData));
        $bar->start();

        foreach ($scrapedData as $url => $scrapedPost) {

            if (!$this->validateScrapedPost($url, $scrapedPost)) {
                $this->failValidation[$url] = $scrapedPost;
                continue;
            }

            $title = $this->parseTitle($scrapedPost);
            $description = $this->parseDescription($scrapedPost);

            if ($this->checkExist($url, $title, $scrapedPost)) {
                $bar->advance();
                continue;
            }

            $category = $this->parseCategory($scrapedPost);

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

            $this->addImages($post, $this->parseImages($scrapedPost));
            $this->addTranslations($post, $title, $description);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }

    private function validateScrapedPost($url, $scrapedPost)
    {
        return true;
    }

    /**
     * Detec already scraped or dublicated post
     *
     */
    private function checkExist($url, $title, $scrapedPost)
    {
        $exists = Post::where('scraped_url', $url)->count();

        if ($exists) {
            $this->alreadyExisted[$url] = $scrapedPost;
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
                $this->alreadyExisted[$url] = $scrapedPost;
                return true;
            }
        }

        return false;
    }

    private function addImages($post, $urls)
    {
        $attachments = [];
        foreach ($urls as $url) {
            if (!$url) {
                continue;
            }
            $disk = Storage::disk('aimages');
            try {
                $contents = file_get_contents($url);
            } catch (\Throwable $th) {
                $this->log(" Can not download image from $url. " . $th->getMessage());
                continue;
            }
            $ext = ProcessImageService::mimeFromUrl($url);

            if (!$ext) {
                $this->log(" Can not download image from $url. Can not autodetermine extension");
                continue;
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
                continue;
            }

            $attachments[] = Attachment::create([
                'attachmentable_id' => $post->id,
                'attachmentable_type' => Post::class,
                'name' => $random_name,
                'original_name' => $name,
                'group' => 'images',
                'type' => 'image',
                'size' => $size
            ]);
        }
        ProcessPostImages::dispatch($attachments);
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
