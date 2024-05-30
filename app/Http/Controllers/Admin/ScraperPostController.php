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

        return $this->jsonSuccess('', ['redirect' => route('admin.scraper-posts.publishing', $next??$scraperPost)]);
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

        $ogLocale = 'en';
        $scraperRun = $scraperPost->run;
        $scraper = $scraperRun->scraper;
        $user = $scraper->user;
        $category = \App\Models\Category::find($request->category_id);

        // get rules for post model
        $rules = (new PostRequest())->rules();

        // remove rules for fiels which will be added automaticaly
        unset($rules['user_id']);
        unset($rules['slug']);
        unset($rules['country']);
        unset($rules['origin_lang']);
        unset($rules['status']);
        unset($rules['meta_description']);
        unset($rules['meta_description.en']);
        unset($rules['meta_title']);
        unset($rules['meta_title.en']);

        // run validation
        $input = $request->validate($rules);

        // add fields
        $input['user_id'] = $user->id;
        $input['status'] = 'approved';

        // calculate meta title and description
        $mTitles = [];
        $mDescriptions = [];
        foreach ($input['title'] as $locale => $title) {
            $mTitles[$locale] = Post::generateMetaTitleHelper($title, $category->name);
        }
        foreach ($input['description'] as $locale => $description) {
            $mDescriptions[$locale] = Post::generateMetaDescriptionHelper($description);
        }

        // escape description
        foreach ($input['description'] as &$desc) {
            $desc = \App\Sanitizer\Sanitizer::handle($desc, false);
        }

        if ($request->created_post_id) {
            $post = Post::findOrFail($request->created_post_id);

            // update metas if new title
            if ($input['title'][$ogLocale] != $post->title) {
                $input['meta_title'] = $mTitles;
                $input['meta_description'] = $mDescriptions;
            }

            $post->update($input);
            $post->saveCosts($input);
            $post->saveTranslations($input);

            if ($request->update_imaged) {
                foreach ($post->images as $img) {
                    $img->delete();
                }
                ScraperImagesToPost::dispatch($scraperPost, $post);
            }
        } else {
            $input['duration'] = 'unlim';
            $input['meta_title'] = $mTitles;
            $input['meta_description'] = $mDescriptions;
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
