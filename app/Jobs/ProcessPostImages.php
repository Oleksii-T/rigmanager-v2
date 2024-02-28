<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Setting;
use App\Models\Attachment;
use Illuminate\Bus\Queueable;
use App\Services\ProcessImageService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessPostImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $images;
    protected $doConvert;
    protected $doWaterMark;
    protected $resizeVersions;
    protected $optimizeSize;

    /**
     * Create a new job instance.
     */
    public function __construct($images)
    {
        $this->images = $images;
        $this->doConvert = Setting::get('convert_uploaded_post_images_to_webp', true, true);
        $this->doWaterMark = Setting::get('add_water_mark_to_uploaded_post_images', true, true);
        $this->resizeVersions = Setting::get('resized_versions_of_uploaded_post_images', true, true);
        $this->optimizeSize = Setting::get('resized_uploaded_post_images', true, true);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->images as $image) {
            $update = $image->optimizations ?? [];

            if ($this->optimizeSize) {
                try {
                    ProcessImageService::resize(1920, null, $image->path);
                    $update['resized'] = true;
                } catch (\Throwable $th) {
                    report($th);
                }
            }

            if ($this->doConvert) {
                try {
                    $image = $this->convert($image);
                    $update['converted'] = true;
                } catch (\Throwable $th) {
                    report($th);
                }
            }

            if ($this->doWaterMark) {
                try {
                    ProcessImageService::watermark($image->path);
                    $update['water_marked'] = true;
                } catch (\Throwable $th) {
                    report($th);
                }
            }

            if ($this->resizeVersions) {
                try {
                    $this->resize($image);
                    $update['resized_copies'] = true;
                } catch (\Throwable $th) {
                    report($th);
                }
            }

            $newSize = filesize($image->path);

            if ($newSize != $image->size) {
                $image->update([
                    'size' => $newSize
                ]);
            }

            if ($update) {
                $image->update([
                    'optimizations' => $update
                ]);
            }
        }
    }

    private function convert($image)
    {
        $newPath = ProcessImageService::convert($image->path);
        if ($newPath == $image->path) {
            return $image;
        }

        $image->update([
            'name' => ProcessImageService::changeExt($image->name),
            'original_name' => ProcessImageService::changeExt($image->original_name)
        ]);

        return $image->fresh();
    }

    private function resize($image)
    {
        $resizes = Attachment::POST_IMG_RESIZES;
        foreach ($resizes as $w => $h) {
            ProcessImageService::resize($w, $w, $image->path);
        }
    }
}
