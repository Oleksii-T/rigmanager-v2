<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\ScraperRun;
use App\Models\ScraperPost;
use Illuminate\Http\Request;
use App\Enums\ScraperPostStatus;
use App\Http\Controllers\Controller;

class ScraperPostController extends Controller
{
    public function publishing(Request $request, ScraperPost $scraperPost)
    {
        $scraperRun = $scraperPost->run;
        $scraper = $scraperRun->scraper;
        $posts = $scraperRun->posts()->get();
        $alreadyPublishedPost = Post::latest()->where('scraped_url', $scraperPost->url)->first();
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
        $next = $scraperPost->run->postToPublish($scraperPost->id);

        if ($request->cancel == 1) {
            $scraperPost->update([
                'status' => ScraperPostStatus::CANCELED
            ]);

            return $this->jsonSuccess('', [
                'redirect' => route('admin.scraper-posts.publishing', $next)
            ]);
        }

        if ($request->skip == 1) {
            return $this->jsonSuccess('', [
                'redirect' => route('admin.scraper-posts.publishing', $next)
            ]);
        }

        //TODO
    }
}
