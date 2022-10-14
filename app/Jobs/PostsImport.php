<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\PostsImport as PostsImportExcel;
use App\Models\Import;
use App\Models\Translation;
use App\Models\Post;
use App\Models\Category;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

            $pages = \Excel::toArray(new PostsImportExcel, $this->import->file->path);
            $rows = $pages[0]; // get first excel page
            $rows = array_slice($rows, 2); // remove the header from import file
            $rows = array_slice($rows, 0, 500); // remove all but first 500 rows
            $posts = [];

            DB::transaction(function () use ($rows, &$posts) {
                foreach ($rows as $row) {
                    if (!$row[1]) {
                        break;
                    }
                    $posts[] = $this->processRow($row);
                }
            });
        } catch (\Throwable $th) {

            \Log::error('PostsImport Job: ERROR', [
                'import' => $this->import,
                'error' => $th->getMessage(),
                'trace' => substr($th->getTraceAsString(), 0, 600)
            ]);

            $this->import->update([
                'status' => Import::STATUS_FAILED
            ]);
        }

        $this->import->update([
            'posts' => $posts,
            'status' => Import::STATUS_DONE
        ]);
    }

    private function processRow($row)
    {
        $disk = Storage::disk('aimages');

        // create post from row
        $post = Post::create([
            'status' => 'pending',
            'is_active' => true,
            'user_id' => $this->user->id,
            'category_id' => $this->category($row[3]),
            'type' => strtolower($row[5]),
            'condition' => strtolower($row[6]),
            'amount' => $row[7],
            'manufacturer' => $row[8],
            'manufactureDate' => $row[9],
            'partNumber' => $row[10],
            'country' => strtolower($row[12]),
            'duration' => strtolower($row[13]),
        ]);


        // save translations
        $post->saveTranslations([
            'slug' => [
                'en' => makeSlug($row[1], Post::allSlugs())
            ],
            'title' => [
                'en' => $row[1]
            ],
            'description' => [
                'en' => $row[2]
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
        foreach (explode(' ', $row[4]) as $url) {
            if (!$url) {
                return;
            }
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
        }

        return $post->id;
    }

    private function category($val)
    {
        return Translation::query()
            ->where('translatable_type', Category::class)
            ->where('field', 'slug')
            ->where('locale', 'en')
            ->where('value', $val)
            ->value('translatable_id');
    }
}
