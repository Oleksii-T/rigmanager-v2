<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Scraper;
use App\Models\ScraperRun;
use App\Models\ScraperLog;
use App\Models\ScraperPost;
use Illuminate\Http\Request;
use App\Enums\ScraperRunStatus;
use App\Enums\ScraperPostStatus;
use App\Services\ScraperService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScraperRequest;

class ScraperRunController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->validate([
            'scraper_id' => ['required', 'exists:scrapers,id']
        ]);
        $input['status'] = ScraperRunStatus::PENDING;
        $scraper = Scraper::find($input['scraper_id']);

        abort_if($scraper->runs()->where('status', ScraperRunStatus::IN_PROGRESS)->exists(), 403, 'Can create more runs');

        $run = $scraper->runs()->create($input);
        \App\Jobs\ScraperJob::dispatch($run);

        return $this->jsonSuccess('Scraper run created successfully', [
            'redirect' => route('admin.scrapers.show', $run->scraper)
        ]);
    }

    public function show(Request $request, ScraperRun $scraperRun)
    {
        if (!$request->ajax()) {
            return view('admin.scraper-runs.show', compact('scraperRun'));
        }

        if ($request->table == 'posts') {
            $posts = $scraperRun->posts();
    
            return ScraperPost::dataTable($posts);
        }

        $logs = $scraperRun->logs();

        return ScraperLog::dataTable($logs);
    }

    public function destroy(Request $request, ScraperRun $scraperRun)
    {
        $scraperRun->delete();

        return $this->jsonSuccess('Scraper Run deleted');
    }
}
