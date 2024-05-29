<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\User;
use App\Enums\PostType;
use App\Models\Scraper;
use App\Enums\PostGroup;
use App\Models\Attachment;
use App\Models\ScraperRun;
use App\Models\ScraperPost;
use App\Jobs\PostTranslate;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Enums\ScraperRunStatus;
use App\Jobs\ProcessPostImages;
use App\Enums\ScraperPostStatus;
use Illuminate\Support\Facades\DB;
use App\Services\TranslationService;
use App\Services\PostScraperService;
use App\Services\ProcessImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ScraperJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    private $runModel;

    public function __construct(ScraperRun $runModel)
    {
        $this->runModel = $runModel;
    }

    public function handle()
    {
        try {
            $run = $this->runModel;
            $scraperModel = $run->scraper;
            $cache = $run->cache_file;

            $this->log('Start Count...');

            // count posts to be scraped
            $postsCount = $this->countPostsToBeScraped();

            $this->log('Count Result', $postsCount);

            // save progress
            $run->update([
                'scraped' => 0,
                'max' => $postsCount,
                'status' => ScraperRunStatus::IN_PROGRESS
            ]);

            $this->log('Start Scrapping...');

            // scrape posts
            $scrapedPosts = $this->scrapePosts();

            $this->log('Posts Scrapped');
            $this->log('Start Save to DB...');

            // saving to db
            $this->saveScrapedPostsToDb($scrapedPosts);

            $this->log('Scraped Posts Saved to DB');

            $run->update([
                'status' => ScraperRunStatus::SUCCESS,
                'end_at' => now()
            ]);

        } catch (\Throwable $th) {

            $run->update([
                'status' => ScraperRunStatus::ERROR,
                'end_at' => now()
            ]);

            $run->logs()->create([
                'text' => 'ERROR: ' . $th->getMessage(),
                'data' => $th->getTraceAsString()
            ]);

            throw $th;
        }
    }

    private function countPostsToBeScraped() : int
    {
        $run = $this->runModel;
        $config = $run->scraper;
        $selectors = collect($config->selectors);
        $postSelector = $selectors->where('name', 'post')->first()['value'];
        $postLinkSelector = $selectors->where('name', 'post_link')->first()['value'];
        $paginationSelector = $selectors->where('name', 'pagination')->first()['value'] ?? '';
        $count = 0;
        foreach ($config['base_urls'] as $url) {
            $count += PostScraperService::make($url)
                ->post($postSelector)
                ->postLink($postLinkSelector)
                ->pagination($paginationSelector)
                ->sleep($config->sleep ?? 0)
                ->count()['posts'];
        }

        return $count;
    }

    private function scrapePosts() : array
    {
        $run = $this->runModel;
        $config = $run->scraper;
        $result = [];
        $selectors = collect($config->selectors);
        $postSelector = $selectors->where('name', 'post')->first()['value'];
        $postLinkSelector = $selectors->where('name', 'post_link')->first()['value'];
        $paginationSelector = $selectors->where('name', 'pagination')->first()['value'] ?? '';

        foreach ($config['base_urls'] as $url) {
            $scrapper = PostScraperService::make($url)
                ->post($postSelector)
                ->postLink($postLinkSelector)
                ->pagination($paginationSelector)
                ->debug($config->is_debug)
                ->afterEachScrape(function ($post) use ($run) {
                    $run->increment('scraped');
                })
                ->logUsing(function ($text) use ($run) {
                    $run->logs()->create([
                        'text' => $text
                    ]);
                });

            if ($config->scrape_limit) {
                $scrapper->limit($config->scrape_limit);
            }

            if ($config->sleep) {
                $scrapper->sleep($config->sleep);
            }

            foreach ($selectors as $selector) {
                if (in_array($selector['name'], Scraper::getDefSelectors())) {
                    continue;
                }
                $scrapper->value(
                    $selector['name'],
                    $selector['value'],
                    $selector['attribute']??null,
                    $selector['is_multiple']??false,
                    $selector['from_posts_page']??false,
                    $selector['required']??false,
                );
            }

            $result = array_merge($result, $scrapper->scrape());
        }

        return $result;
    }

    private function saveScrapedPostsToDb($scrapedData)
    {
        foreach ($scrapedData as $url => $scrapedPostData) {
            $this->runModel->posts()->create([
                'url' => $url,
                'status' => ScraperPostStatus::PENDING,
                'data' => $scrapedPostData
            ]);
        }
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

        $condition = $this->parseCondition($scrapedPost);

        $country = $this->parseCountry($scrapedPost);

        $post = [
            'user_id' => $this->user->id,
            'group' => PostGroup::EQUIPMENT,
            'status' => 'pending',
            'duration' => 'unlim',
            'is_active' => true,
            'origin_lang' => 'en',
            'category_id' => $category->id,
            'type' => PostType::SELL,
            'condition' => $condition,
            'country' => $country,
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

    private function parseCondition($scrapedPost)
    {
        return 'new';
    }

    private function addSavedImages($post, $paths)
    {
        if (!$paths) {
            return;
        }

        $attachments = [];

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
        ProcessPostImages::dispatch($attachments);
    }

    private function addTranslations($post, $title, $description)
    {
        $textLocale = (new TranslationService())->detectLanguage("$title. $description");
        $mTitle = Post::generateMetaTitleHelper($title, $post->category->name);

        $post->saveTranslations([
            'slug' => [
                $textLocale => makeSlug($title, Post::allSlugs())
            ],
            'title' => [
                $textLocale => $title
            ],
            'description' => [
                $textLocale => $description
            ],
            'meta_title' => [
                $textLocale => $mTitle
            ],
            'meta_description' => [
                $textLocale => $description ? Post::generateMetaDescriptionHelper($description) : $mTitle
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

    public function descriptionEscape($desc)
    {
        foreach ($this->getEscapedChars() as $esc) {
            if ($esc[2]) {
                $desc = preg_replace($esc[0], $esc[1], $desc);
            } else {
                $desc = str_replace($esc[0], $esc[1], $desc);
            }
        }

        $desc = \App\Sanitizer\Sanitizer::handle($desc, false);
        $desc = preg_replace('/<p>[ \n]*<\/p>/', '', $desc); // remove empty paragraphs
        $desc = trim($desc);

        return $desc;
    }

    private function log(string $text, $data=[])
    {
        return $this->runModel->logs()->create([
            'text' => $text,
            'data' => $data ?: null
        ]);

        $toLog = $text;

        if ($data) {
            $toLog .= (': ' . json_encode($data));
        }

        \Log::channel('scraping')->info($toLog);

    }

    public static function getEscapedChars()
    {
        return [
            ["\r\n", "\n", false], // ensure there all the same new lines symbo
            ["\t", ' ', false], // change tabs to space
            ['&Acirc;', '', false],
            ['&nbsp;', ' ', false],
            ["\u{A0}", ' ', false], // same as &nbsp;

            ['&acirc;&#128;&cent;', '- ', false],
            ['&atilde;&#128;&#129;', ', ', false],
            ['&iuml;&frac14;&#154;', ': ', false],
            ['&iuml;&frac12;&#158;', '~', false],
            ['&iuml;&frac14;&#155;', '; ', false],
            ['&atilde;&#128;&#130;', '. ', false],
            ['&acirc;&#128;&#157;', '"', false],
            ['&acirc;&#128;&sup3;', '" ', false], // inches
            ['&acirc;&#128;&#156;', '"', false],
            ['&acirc;&#128;&#153;', "'", false],  // apostrophy
            ['&amp;', '&', false],
            ['&iuml;&frac14;&#140;', ', ', false],
            ['&iuml;&frac14;&#136;', ' (', false],
            ['&iuml;&frac14;&#137;', ') ', false],
            ['&iuml;&frac14;&#141;', '-', false],
            ['&iuml;&frac14;&#139;', '+', false],
            ['&acirc;&#132;&#131;', 'C', false],
            ['&iuml;&frac14;&#156;', '<', false],
            ['&iuml;&#129;&not;', '', false],
            ['&atilde;&#128;&#157;', '"', false],
            ['&atilde;&#128;&#158;', '"', false],
            ['&acirc;&#129;&#132;', '/', false],
            ['&acirc;&#129;&#132;', '/', false],
            ['&middot;', '- ', false],
            ['&acirc;&#128;&#148;', '-', false],

            [chr(195).chr(131).chr(194).chr(151), 'x', false],
            [chr(195).chr(142).chr(194).chr(188), 'µ', false],
            [chr(195).chr(142).chr(194).chr(169), 'Ω', false],
            [chr(195).chr(143).chr(194).chr(134), 'φ', false],
            [chr(195).chr(131).chr(194).chr(184), 'ø', false],
            [chr(195).chr(142).chr(194).chr(166), 'Φ', false],
            [chr(195).chr(131).chr(194).chr(152), 'Ø', false],
            [chr(195).chr(142).chr(194).chr(148), 'Δ', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(136), ' (', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(137), ') ', false],
            [chr(195).chr(162).chr(194).chr(137).chr(194).chr(164), '≤', false],
            [chr(195).chr(162).chr(194).chr(137).chr(194).chr(165), '≥', false],
            [chr(195).chr(162).chr(194).chr(128).chr(194).chr(147), '-', false],
            [chr(195).chr(162).chr(194).chr(132).chr(194).chr(131), '℃', false],
            [chr(195).chr(175).chr(194).chr(129).chr(194).chr(172), '', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(133), '%', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(133), '%', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(156), '<', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(141), '-', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(139), '+', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(140), ', ', false],
            [chr(195).chr(165).chr(194).chr(163).chr(194).chr(171), '±', false],
            [chr(195).chr(162).chr(194).chr(150).chr(194).chr(179), '△', false],
            [chr(195).chr(142).chr(194).chr(148).chr(194).chr(176), 'Δ°', false],
            [chr(195).chr(175).chr(194).chr(185).chr(194).chr(163), '﹣', false],
            [chr(195).chr(162).chr(194).chr(150).chr(194).chr(161), '', false],
            [chr(195).chr(162).chr(194).chr(150).chr(194).chr(161), '', false],
            [chr(195).chr(162).chr(194).chr(136).chr(194).chr(163), '|', false],
            [chr(195).chr(175).chr(194).chr(185).chr(194).chr(159), '#', false],
            [chr(195).chr(163).chr(194).chr(128).chr(194).chr(158), '"', false],
            [chr(195).chr(162).chr(194).chr(133).chr(194).chr(161), 'Ⅱ', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(142), '.', false],
            [chr(195).chr(162).chr(194).chr(133).chr(194).chr(162), 'Ⅲ', false],
            [chr(195).chr(175).chr(194).chr(188).chr(194).chr(158), '>', false],
            // [, false],


            ['/^[ \n]*/', "", true],  // remove leading newlines and spaces
            ['/ +\n/', "\n", true], // remove spaces before new lines
            ['/(\n){3,}/', "\n\n", true], // remove dublicated new lines
            ["\n", "\r\n", false], // make Windows friendly new lines
        ];
    }
}
