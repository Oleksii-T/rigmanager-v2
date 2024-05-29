<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Enums\PostGroup;
use App\Models\ScraperPost;
use Illuminate\Http\Request;
use App\Enums\ScraperPostStatus;
use App\Jobs\ScraperImagesToPost;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;

class ScraperPostController extends Controller
{
    public function publishing(Request $request, ScraperPost $scraperPost)
    {
        $scraperRun = $scraperPost->run;
        $scraper = $scraperRun->scraper;
        $posts = $scraperRun->posts()->get();
        $alreadyPublishedPost = Post::latest()->where('user_id', $scraper->user_id)->where('scraped_url', $scraperPost->url)->first();
        list($categsFirstLevel, $categsSecondLevel, $categsThirdLevel) = \App\Models\Category::getLevels();
        $activeLevels = $alreadyPublishedPost
            ? array_column($alreadyPublishedPost->category->parents(true), 'id')
            : null;

        return view('admin.scraper-posts.publish', compact(
            'scraperRun',
            'scraper',
            'posts',
            'scraperPost',
            'alreadyPublishedPost',
            'categsFirstLevel',
            'categsSecondLevel',
            'categsThirdLevel',
            'activeLevels'
        ));
    }

    public function publish(Request $request, ScraperPost $scraperPost)
    {
        DB::transaction(fn () => $this->publishHelper($request, $scraperPost));

        $next = $scraperPost->run->postToPublish($scraperPost->id);

        return $this->jsonSuccess('', ['redirect' => route('admin.scraper-posts.publishing', $next)]);
    }

    private function publishHelper($request, $scraperPost)
    {
        if ($request->skip == 1) {
            return;
        }

        if ($request->cancel == 1) {
            $scraperPost->update([
                'status' => ScraperPostStatus::CANCELED
            ]);

            return;
        }

        $scraperRun = $scraperPost->run;
        $scraper = $scraperRun->scraper;
        $user = $scraper->user;

        // get rules for post model
        $rules = (new PostRequest())->rules();

        // remove rules for fiels which will be added automaticaly
        unset($rules['user_id']);
        unset($rules['slug']);
        unset($rules['country']);
        unset($rules['origin_lang']);
        unset($rules['status']);

        // run validation
        $input = $request->validate($rules);

        // add fields
        $input['user_id'] = $user->id;
        $input['status'] = 'approved';
        foreach ($input['description'] as &$desc) {
            $desc = \App\Sanitizer\Sanitizer::handle($desc, false);
        }

        if ($request->created_post_id) {
            $post = Post::findOrFail($request->created_post_id);

            $post->update($input);
            $post->saveCosts($input);
            $post->saveTranslations($input);

            if ($request->update_imaged) {
                // remove old images
                foreach ($post->images as $img) {
                    $img->delete();
                }

                // download new images
                ScraperImagesToPost::dispatch($scraperPost, $post);
            }
        } else {
            $input['duration'] = 'unlim';
            $input['slug'] = [];
            foreach ($input['title'] as $locale => $title) {
                $allSlugs = \App\Models\Translation::query()
                    ->where('field', 'slug')
                    ->where('locale', $locale)
                    ->where('translatable_type', Post::class)
                    ->pluck('value')
                    ->toArray();
                $input['slug'][$locale] = makeSlug($title, $allSlugs);
            }
            $input['scraped_url'] = $scraperPost->url;
            $input['country'] = $user->country;
            $input['origin_lang'] = 'en';
            $input['group'] = PostGroup::EQUIPMENT;

            $post = Post::create($input);
            $post->saveCosts($input);
            $post->saveTranslations($input);
            ScraperImagesToPost::dispatch($scraperPost, $post);
        }

        $scraperPost->update([
            'status' => ScraperPostStatus::PUBLISHED
        ]);
    }
}
