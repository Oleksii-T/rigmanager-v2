<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Import;
use App\Models\Category;
use App\Models\Attachment;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use App\Enums\NotificationGroup;
use Illuminate\Support\Facades\DB;
use App\Services\TranslationService;
use App\Services\ProcessImageService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Imports\PostsImport as PostsImportExcel;

class PostsImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $import;
    protected $user;
    protected $startRow;
    protected $endRow;
    protected $userColumns;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import)
    {
        $this->import = $import;
        $s = $import->settings;
        $this->startRow = $s['start_row'];
        $this->endRow = $s['end_row'];
        $this->userColumns = $s['columns'];
        $this->user = $import->user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->import->update([
                'status' => Import::STATUS_PROCESSING
            ]);

            $this->log('Start');

            $pages = \Excel::toArray(new PostsImportExcel, $this->import->file->path);
            $rows = $pages[0]; // get first excel page
            // get only rows specified by user
            $rows = array_slice($rows, $this->startRow-1, $this->endRow-$this->startRow+1);
            $posts = [];

            $this->log('Total rows: ' . count($rows), ' ');

            DB::transaction(function () use ($rows, &$posts) {
                foreach ($rows as $i => $row) {
                    if (!$row[1]) {
                        break;
                    }
                    $this->log("Process row #$i", '  ');
                    $posts[] = $this->processRow($row);
                }
            });
        } catch (\Throwable $th) {

            $this->log('ERROR. see main log for more info', ' ');

            \Log::error('PostsImport Job: ERROR', [
                'import' => $this->import,
                'error' => $th->getMessage(),
                'trace' => substr($th->getTraceAsString(), 0, 600)
            ]);

            Notification::make($this->user->id, NotificationGroup::IMPORT_FAIL, [
                'vars' => [
                    'id' => $this->import->id
                ]
            ], $this->import);

            $this->import->update([
                'status' => Import::STATUS_FAILED
            ]);

            return;
        }

        $this->log('DONE', ' ');

        Notification::make($this->user->id, NotificationGroup::IMPORT_SUCCESS, [
            'vars' => [
                'id' => $this->import->id
            ]
        ], $this->import);

        $this->import->update([
            'posts' => $posts,
            'status' => Import::STATUS_DONE
        ]);
    }

    private function processRow($row)
    {
        // create post from row
        $post = Post::create([
            'status' => 'pending',
            'duration' => 'unlim',
            'is_active' => true,
            'user_id' => $this->user->id,
            'origin_lang' => 'en',
            'category_id' => $this->getCategory($row[$this->userColumns['category']]),
            'type' => $this->getType($row),
            'condition' => $this->getCondition($row),
            'amount' => $this->getAmount($row),
            'manufacturer' => $this->getManufacturer($row),
            'manufactureDate' => $this->getManufactureDate($row),
            'partNumber' => $this->getPartNumber($row),
            'country' => $this->getCountry($row)
        ]);

        $this->addTranslations($post, $row);
        $this->addCosts($post, $row);
        $this->addImages($post, $row);

        return $post->id;
    }

    private function getCategory($val)
    {
        return Translation::query()
            ->where('translatable_type', Category::class)
            ->where('locale', 'en')
            ->where('value', 'like', "%$val%")
            ->whereIn('field', ['slug', 'name'])
            ->value('translatable_id');
    }

    private function getType($row)
    {
        $i = $this->userColumns['type'];
        $default = 'sell';

        if (!$i) {
            return $default;
        }

        $val = strtolower($row[$i]);
        $val = trim($val);

        return $val ? $val : $default;
    }

    private function getCondition($row)
    {
        $i = $this->userColumns['condition'];
        $default = 'new';

        if (!$i) {
            return $default;
        }

        $val = strtolower($row[$i]);
        $val = trim($val);

        return $val ? $val : $default;
    }

    private function getAmount($row)
    {
        $i = $this->userColumns['amount'];
        $default = null;

        if (!$i) {
            return $default;
        }

        $val = trim($row[$i]);

        return $val ? $val : $default;
    }

    private function getManufacturer($row)
    {
        $i = $this->userColumns['manufacturer'];
        $default = null;

        if (!$i) {
            return $default;
        }

        $val = trim($row[$i]);

        return $val ? $val : $default;
    }

    private function getManufactureDate($row)
    {
        $i = $this->userColumns['manufactureDate'];
        $default = null;

        if (!$i) {
            return $default;
        }

        $val = trim($row[$i]);

        return $val ? $val : $default;
    }

    private function getPartNumber($row)
    {
        $i = $this->userColumns['partNumber'];
        $default = null;

        if (!$i) {
            return $default;
        }

        $val = trim($row[$i]);

        return $val ? $val : $default;
    }

    private function getCountry($row)
    {
        $i = $this->userColumns['country'];
        $default = $this->user->country;

        if (!$i) {
            return $default;
        }

        $val = strtolower($row[$i]);
        $val = trim($val);

        return $val ? $val : $default;
    }

    private function addTranslations($post, $row)
    {
        $title = $row[$this->userColumns['title']];
        $description = $row[$this->userColumns['description']];
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

    private function addCosts($post, $row)
    {
        $i = $this->userColumns['cost'];

        if (!$i) {
            return;
        }

        $val = trim($row[$i]);

        if (!$val) {
            return;
        }

        $post->saveCosts([
            'cost' => substr($val, 1),
            'currency' => array_search($val[0], currencies()),
        ]);
    }

    private function addImages($post, $row)
    {
        $i = $this->userColumns['images'];

        if (!$i) {
            return;
        }

        $imagesRaw = trim($row[$i]);

        if (!$imagesRaw) {
            return;
        }

        $this->log("Store images", '   ');
        $disk = Storage::disk('aimages');
        $resultImages = [];
        $images = [];
        //? use preg_split to split by two chars
        foreach (explode(' ', $imagesRaw) as $i) {
            $res = explode("\n", $i);
            count($res) == 1
                ? $images[] = $i
                : $images = array_merge($images, $res);
        }

        foreach ($images as $url) {
            if (!$url) {
                continue;
            }
            $pattern = '/\s*/m';
            $replace = '';
            $url = preg_replace($pattern, $replace, $url);
            $url = trim($url);

            if (!$url) {
                continue;
            }
            try {
                $contents = file_get_contents($url);
                $ext = ProcessImageService::mimeFromUrl($url);

                if (!$ext) {
                    abort(500, 'Can not detect extension');
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
                    continue;
                }

                $resultImages[] = Attachment::create([
                    'attachmentable_id' => $post->id,
                    'attachmentable_type' => Post::class,
                    'name' => $random_name,
                    'original_name' => $name,
                    'group' => 'images',
                    'type' => 'image',
                    'size' => $size
                ]);
            } catch (\Throwable $th) {
                $this->log('Image fail. see main log for details', '    ');
                \Log::error("Can not download image from import file.", [
                    'urltext' => $url,
                    'user' => auth()->id() ?? '0',
                    'error' => $th->getMessage(),
                    'trace' => substr($th->getTraceAsString(), 0, 600)
                ]);
            }
        }

        if ($resultImages) {
            ProcessPostImages::dispatch($resultImages);
        }
    }

    private function log($text, $prefix='')
    {
        \Log::channel('importing')->info("$prefix Import #" . $this->import->id . ": $text");
    }
}
