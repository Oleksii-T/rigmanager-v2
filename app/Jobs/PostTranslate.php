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
    protected $oldTrans;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post, $oldTrans=[])
    {
        $this->post = $post;
        $this->oldTrans = $oldTrans;
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

        dlog(' PostTranslate@handle. START', [
            'id' => $p->id,
            'origin_lang' => $p->origin_lang,
            'title' => $p->translated('title', $p->origin_lang),
            'description' => $p->translated('description', $p->origin_lang)
        ]); //!LOG

        $translator = new TranslationService();
        $toLocales = array_diff(LaravelLocalization::getSupportedLanguagesKeys(), [$p->origin_lang]);
        $translatables = Post::TRANSLATABLES;
        $allPostSlugs = Post::allSlugs();
        $translations = [];

        foreach ($translatables as $translatable) {
            $oldValue = $this->oldTrans[$translatable] ?? null;
            $origin = $p->translated($translatable, $p->origin_lang);
            if ($oldValue && $oldValue == $origin) {
                dlog("  PostTranslate@handle. $translatable not changed - skip translation", ); //!LOG
                continue;
            }
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

        dlog('  PostTranslate@handle. END', $translations); //!LOG

        $p->saveTranslations($translations);
    }
}
