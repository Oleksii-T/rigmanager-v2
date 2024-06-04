<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Attachment;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use App\Jobs\ProcessPostImages;
use App\Services\ProcessImageService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ScraperImagesToPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $scraperPost;
    protected $post;

    /**
     * Create a new job instance.
     */
    public function __construct($scraperPost, $post)
    {
        $this->scraperPost = $scraperPost;
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        dlog("ScraperImagesToPost@handle"); //! LOG
        $attachments = [];
        $scraper = $this->scraperPost->run->scraper;
        $imgAttrs = ['src', 'data-original'];
        $imageSelectors = array_filter($scraper->selectors, fn ($a) => in_array($a['attribute']??'', $imgAttrs));
        $imageSelectors = array_column($imageSelectors, 'name');
        $urls = [];

        foreach ($this->scraperPost->data as $key => $scraperPostData) {
            if (!in_array($key, $imageSelectors)) {
                continue;
            }

            if (is_array($scraperPostData)) {
                $urls = array_merge($urls, $scraperPostData);
            } else {
                $urls[] = $scraperPostData;
            }
        }

        dlog(" urls", $urls); //! LOG

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
                $this->scraperPost->run->logs()->create([
                    'text' => " Can not download image from $url. Invalid extension"
                ]);
                continue;
            }

            $attachments[] = Attachment::create([
                'attachmentable_id' => $this->post->id,
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
}
