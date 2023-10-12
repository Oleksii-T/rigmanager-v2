<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\Attachment;
use Illuminate\Bus\Queueable;
use App\Services\ProcessImageService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessUserImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $avatar;
    protected $banner;

    /**
     * Create a new job instance.
     */
    public function __construct($avatar=null, $banner=null)
    {
        $this->avatar = $avatar;
        $this->banner = $banner;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $optimizeAvatar = Setting::get('optimize_user_avatar', true, true);
        $optimizeBanner = Setting::get('optimize_user_banner', true, true);
        $avatar = $this->avatar;
        $banner = $this->banner;

        if ($optimizeAvatar && $avatar) {
            $this->optimize($avatar, Attachment::AVATAR_RESIZES);
        }

        if ($optimizeBanner && $banner) {
            $this->optimize($banner, Attachment::USER_BANNER_RESIZES);
        }
    }

    private function optimize($image, $resizes)
    {
        $image = $this->convert($image);
        foreach ($resizes as $w => $h) {
            ProcessImageService::resize($w, $h, $image->path);
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
}
