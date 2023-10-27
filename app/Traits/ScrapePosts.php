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
    private function process($scrapedPosts)
    {
        $count = count($scrapedPosts);
        if (!$this->confirm("Found $count posts. Proceed?")) {
            return;
        }

        $this->importScrapedPosts($scrapedPosts);

        $this->info("Successfully processed $count posts.");
        if ($this->skipped) {
            $this->warn("Skipped: $this->skipped");
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
            $title = $this->parseTitle($scrapedPost);

            if ($this->checkExist($url, $title)) {
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
            $this->addTranslations($post, $title, $this->parseDescription($scrapedPost));

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }

    /**
     * Detec already scraped or dublicated post
     *
     */
    private function checkExist($url, $title)
    {
        $exists = Post::where('scraped_url', $url)->count();

        if ($exists) {
            // $this->info("$url - EXISTS by url");
            $this->skipped++;
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
                // $this->info("$url - EXISTS by title '$title' in post #$post->id");
                $this->skipped++;
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
