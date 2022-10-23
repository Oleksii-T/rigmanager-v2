<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use App\Services\TranslationService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PostTranslate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $p = $this->post;

        if (!$p->auto_translate) {
            return;
        }

        $translator = new TranslationService();
        $toLocales = array_diff(LaravelLocalization::getSupportedLanguagesKeys(), [$p->origin_lang]);
        $translatables = Post::TRANSLATABLES;
        $allPostSlugs = Post::allSlugs();
        $translations = [];

        foreach ($translatables as $translatable) {
            $origin = $p->translated($translatable, $p->origin_lang);
            $translated = [];
            foreach ($toLocales as $toLocale) {
                $res = $translator->translate($origin, $toLocale);
                if ($translatable == 'slug') {
                    $res = makeSlug($res, $allPostSlugs);
                }
                $translated[$toLocale] = $res;
            }

            $translations[$translatable] = $translated;
        }

        $p->saveTranslations($translations);
    }
}
