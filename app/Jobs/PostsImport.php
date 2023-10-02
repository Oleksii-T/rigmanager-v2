<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\PostsImport as PostsImportExcel;
use App\Enums\NotificationGroup;
use App\Models\Notification;
use App\Models\Import;
use App\Models\Translation;
use App\Models\Post;
use App\Models\Category;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\TranslationService;

class PostsImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $import;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import)
    {
        $this->import = $import;
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
            $rows = array_slice($rows, 2); // remove the header from import file
            $rows = array_slice($rows, 0, 500); // remove all but first 500 rows
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

    private function log($text, $prefix='')
    {
        \Log::channel('importing')->info("$prefix Import #" . $this->import->id . ": $text");
    }

    private function processRow($row)
    {
        $translator = new TranslationService();
        $textLocale = $translator->detectLanguage($row[1] . '. ' . $row[2]);

        // create post from row
        $post = Post::create([
            'status' => 'pending',
            'is_active' => true,
            'user_id' => $this->user->id,
            'origin_lang' => $textLocale,
            'category_id' => $this->category($row[3]),
            'type' => strtolower($row[5]),
            'condition' => strtolower($row[6]),
            'amount' => $row[7],
            'manufacturer' => $row[8],
            'manufactureDate' => $row[9],
            'partNumber' => $row[10],
            'country' => $row[12] ? strtolower($row[12]) : $this->user->country,
            'duration' => strtolower($row[13]),
        ]);

        // save translations
        $post->saveTranslations([
            'slug' => [
                $textLocale => makeSlug($row[1], Post::allSlugs())
            ],
            'title' => [
                $textLocale => $row[1]
            ],
            'description' => [
                $textLocale => $row[2]
            ]
        ]);
        PostTranslate::dispatch($post);

        // attach prices in all currencies
        if ($row[11]) {
            $post->saveCosts([
                'cost' => substr($row[11], 1),
                'currency' => array_search($row[11][0], currencies()),
            ]);
        }

        // attach images from url
        $this->storeImages($post, $row[4]);

        return $post->id;
    }

    private function storeImages($post, $imagesRaw)
    {
        if (!$imagesRaw) {
            return;
        }
        $this->log("Store images", '   ');
        $disk = Storage::disk('aimages');
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
                $name = substr($url, strrpos($url, '/') + 1);
                $ext = substr($name, strrpos($name, '.'));
                $random_name = Str::random(40) . $ext;

                $disk->put($random_name, $contents);

                $size = $disk->size($random_name);
                $mime = $disk->mimeType($random_name);

                if (!in_array($mime, ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'])) {
                    $disk->delete($random_name);
                    continue;
                }

                Attachment::create([
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
    }

    private function category($val)
    {
        return Translation::query()
            ->where('translatable_type', Category::class)
            ->where('locale', 'en')
            ->where(function ($q) use($val){
                $q->where(function ($q2) use($val){
                    $q2->where('field', 'slug')->where('value', 'like', "%$val%");
                })->orWhere(function ($q2) use($val){
                    $q2->where('field', 'name')->where('value', 'like', "%$val%");
                });
            })
            ->value('translatable_id');
    }
}
