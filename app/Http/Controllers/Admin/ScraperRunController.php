<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Scraper;
use App\Models\ScraperRun;
use App\Models\ScraperLog;
use App\Models\ScraperPost;
use Illuminate\Http\Request;
use App\Enums\ScraperRunStatus;
use App\Http\Controllers\Controller;

class ScraperRunController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->validate([
            'scraper_id' => ['required', 'exists:scrapers,id'],
            'scraper_debug_enabled' => ['required', 'bool'],
            'only_count' => ['required', 'bool'],
            'scrape_limit' => ['nullable', 'numeric'],
        ]);
        $input['status'] = ScraperRunStatus::PENDING;
        $scraper = Scraper::find($input['scraper_id']);

        abort_if($scraper->runs()->where('status', ScraperRunStatus::IN_PROGRESS)->exists(), 403, 'Can not create more runs');

        $run = $scraper->runs()->create($input);
        \App\Jobs\ScraperJob::dispatch($run);

        return $this->jsonSuccess('Scraper run created successfully', [
            'redirect' => route('admin.scrapers.show', $run->scraper)
        ]);
    }

    public function show(Request $request, ScraperRun $scraperRun)
    {
        if (!$request->ajax()) {
            $scraperPostToPublish = $scraperRun->postToPublish() ?? $scraperRun->posts()->first();
            return view('admin.scraper-runs.show', compact('scraperRun', 'scraperPostToPublish'));
        }

        if ($request->table == 'posts') {
            $posts = $scraperRun->posts();

            return ScraperPost::dataTable($posts);
        }

        $logs = $scraperRun->logs();

        return ScraperLog::dataTable($logs);
    }

    public function extra(Request $request, ScraperRun $scraperRun)
    {
        if (!$request->ajax()) {
            return view('admin.scraper-runs.extra', compact('scraperRun'));
        }

        $scrapedUrls = $scraperRun->posts()->pluck('url')->toArray();

        $posts = Post::query()
            ->whereNotNull('scraped_url')
            ->whereNotIn('scraped_url', $scrapedUrls)
            ->where('user_id', $scraperRun->scraper->user_id);

        return Post::dataTable($posts);
    }

    public function destroy(Request $request, ScraperRun $scraperRun)
    {
        $scraperRun->delete();

        return $this->jsonSuccess('Scraper Run deleted');
    }
}
