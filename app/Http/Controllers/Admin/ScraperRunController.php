<?php

namespace App\Http\Controllers\Admin;

use App\Models\Scraper;
use App\Models\ScraperRun;
use Illuminate\Http\Request;
use App\Enums\ScraperRunStatus;
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
        return view('admin.scraper-runs.show', compact('scraperRun'));
    }

    public function destroy(Request $request, ScraperRun $scraperRup)
    {
        //
    }
}
