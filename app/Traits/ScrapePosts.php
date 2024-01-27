<?php

namespace App\Traits;

use App\Models\Post;
use App\Models\User;
use App\Enums\PostType;
use App\Enums\PostGroup;
use App\Models\Attachment;
use App\Jobs\PostTranslate;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Jobs\ProcessPostImages;
use Illuminate\Support\Facades\DB;
use App\Services\TranslationService;
use App\Services\ProcessImageService;
use Illuminate\Support\Facades\Storage;

trait ScrapePosts
{
    private $user = null;
    private $alreadyExisted = [];
    private $failValidation = [];
    private $cacheFile;
    private $scrapeLimit;
    private $importLimit;
    private $sleep;
    private $userEmail;
    private $ignoreCache;
    private $scraperDebug;

    private function setOptions()
    {
        $userId = $this->option('user');
        $user = User::where('email', $userId)->orWhere('id', $userId)->first();

        if (!$user) {
            $this->error("User '$userId' not found");
            die();
        }

        $this->ignoreCache = $this->option('ignore-cache');
        $this->scraperDebug = $this->option('scraper-debug');
        $this->cacheFile = base_path($this->option('cache-file'));
        $this->scrapeLimit = $this->option('scrape-limit');
        $this->importLimit = $this->option('import-limit');
        $this->sleep = $this->option('sleep');

        $this->warn("User: #$user->id $user->name <$user->email>");
        $this->warn("Ignore cache file? " . ($this->ignoreCache ? 'yes' : 'no'));
        $this->warn("Cache file path: $this->cacheFile");
        $this->warn("Scrape Limit: " . (!$this->scrapeLimit ? 'none' : $this->scrapeLimit));
        $this->warn("Import Limit: " . (!$this->importLimit ? 'none' : $this->importLimit));
        $this->warn("Sleep before scrape: " . (!$this->sleep ? 'none' : $this->sleep));

        if (!$this->confirm('Please confirm config above')) {
            die();
        }

        $this->user = $user;
    }

    private function porocess()
    {
        if (!$this->ignoreCache && file_exists($this->cacheFile)) {
            $this->info("Cache file detected. Loading data...");
            $json = file_get_contents($this->cacheFile);
            $scrapedPosts = json_decode($json, true);
            $this->line(" Done.");
        } else {
            $this->warn("Cache file NOT found. Web scrappping...");
            $scrapedPosts = $this->scrapePosts();
            $json = json_encode($scrapedPosts);
            $fp = fopen($this->cacheFile, 'w');
            fwrite($fp, $json);
            fclose($fp);
            $this->line(" Posts been cashed into cashe file.");
            $this->line(" Done.");
        }

        //! dev
        // $firstKey = array_keys($scrapedPosts)[0];
        // $tmp = [
        //     $firstKey => $scrapedPosts[$firstKey]
        // ];
        // $scrapedPosts =  $tmp;

        $count = count($scrapedPosts);
        if (!$this->confirm("Found $count posts. Proceed to importing?")) {
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
        $importedCount = 0;

        foreach ($scrapedData as $url => $scrapedPost) {
            $isImported = DB::transaction(fn () => $this->importScrapedPost($url, $scrapedPost));

            $bar->advance();

            if ($isImported) {
                $importedCount++;
            }

            if ($this->importLimit && $importedCount >= $this->importLimit) {
                $this->warn("Importing limit reached");
                break;
            }
        }

        $bar->finish();
        $this->newLine(2);
    }

    private function importScrapedPost($url, $scrapedPost)
    {
        if (!$this->validateScrapedPost($scrapedPost)) {
            $this->failValidation[$url] = $scrapedPost;
            return false;
        }

        $title = $this->parseTitle($scrapedPost);
        $description = $this->parseDescription($scrapedPost);

        if ($this->checkExist($url, $title, $scrapedPost)) {
            return false;
        }

        $category = $this->parseCategory($scrapedPost);

        $post = [
            'user_id' => $this->user->id,
            'group' => PostGroup::EQUIPMENT,
            'status' => 'pending',
            'duration' => 'unlim',
            'is_active' => true,
            'origin_lang' => 'en',
            'category_id' => $category->id,
            'type' => PostType::SELL,
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
        $this->addSavedImages($post, $this->parseSavedImages($scrapedPost));
        $this->addCosts($post, $scrapedPost);
        $this->addTranslations($post, $title, $description);

        return true;
    }

    private function validateScrapedPost($scrapedPost)
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
            if (!$post) {
                continue;
            }
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

    private function parseSavedImages($scrapedPost)
    {
        return [];
    }

    private function addSavedImages($post, $paths)
    {
        if (!$paths) {
            return;
        }

        foreach ($paths as $path) {
            $disk = Storage::disk('aimages');

            $fileName = basename($path);
            copy($path, $disk->path($fileName));
            $size = $disk->size($fileName);

            $attachments[] = Attachment::create([
                'attachmentable_id' => $post->id,
                'attachmentable_type' => Post::class,
                'name' => $fileName,
                'original_name' => $fileName,
                'group' => 'images',
                'type' => 'image',
                'size' => $size
            ]);
        }

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

    private function addCosts($post, $scrapedPost)
    {
        return;
    }

    private function descriptionEscape($desc, $removeTable=true)
    {
        if ($removeTable) {
            $startTable = strpos($desc, '<table');
            while ($startTable !== false) {
                $endTable = strpos($desc, '</table>');
                if ($startTable !== false && $endTable !== false) {
                    $desc = substr($desc, 0, $startTable) . substr($desc, $endTable+8);
                }
                $startTable = strpos($desc, '<table');
            }
        }

        $desc = strip_tags($desc);
        $desc = str_replace("\r\n", "\n", $desc); // ensure there all the same new lines symbol
        $desc = str_replace("\t", ' ', $desc); // change tabs to space
        $desc = str_replace('&Acirc;', '', $desc);
        $desc = str_replace('&nbsp;', ' ', $desc);
        $desc = str_replace("\u{A0}", ' ', $desc); // same as &nbsp;
        $desc = str_replace('&acirc;&#128;&cent;', '- ', $desc);
        $desc = str_replace('&acirc;&#128;&#157;', '"', $desc);
        $desc = str_replace('&acirc;&#128;&sup3;', '" ', $desc); // inches
        $desc = str_replace('&acirc;&#128;&#156;', '"', $desc);
        $desc = str_replace('&acirc;&#128;&#153;', "'", $desc); // apostrophy
        $desc = preg_replace('/^[ \n]*/', "", $desc); // remove leading newlines and spaces
        $desc = preg_replace('/ +\n/', "\n", $desc); // remove spaces before new lines
        $desc = preg_replace('/(\n){3,}/', "\n\n", $desc); // remove dublicated new lines
        $desc = str_replace("\n", "\r\n", $desc); // make Windows friendly new lines

        return $desc;
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
