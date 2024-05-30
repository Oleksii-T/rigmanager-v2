<?php

namespace App\Http\Controllers\Admin;

use App\Models\Scraper;
use App\Models\ScraperRun;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Enums\ScraperRunStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScraperRequest;

class ScraperController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.scrapers.index');
        }

        $scrapers = Scraper::query();

        return Scraper::dataTable($scrapers);
    }

    public function create()
    {
        return view('admin.scrapers.create');
    }

    public function store(ScraperRequest $request)
    {
        $input = $request->validated();
        $input['base_urls'] = explode(',', $input['base_urls']);
        $input['base_urls'] = array_map('trim', $input['base_urls']);
        Scraper::create($input);

        return $this->jsonSuccess('Scraper created successfully', [
            'redirect' => route('admin.scrapers.index')
        ]);
    }

    public function show(Request $request, Scraper $scraper)
    {
        if (!$request->ajax()) {
            $runInProgress = $scraper->runs()->where('status', ScraperRunStatus::IN_PROGRESS)->first();

            return view('admin.scrapers.show', compact('scraper', 'runInProgress'));
        }

        $runs = $scraper->runs();

        return ScraperRun::dataTable($runs);
    }

    public function edit(Scraper $scraper)
    {
        return view('admin.scrapers.edit', compact('scraper'));
    }

    public function update(ScraperRequest $request, Scraper $scraper)
    {
        $input = $request->validated();
        $input['base_urls'] = explode(',', $input['base_urls']);
        $input['base_urls'] = array_map('trim', $input['base_urls']);
        $scraper->update($input);

        return $this->jsonSuccess('Scraper updated successfully');
    }

    public function destroy(Scraper $scraper)
    {
        $scraper->delete();

        return $this->jsonSuccess('Scraper deleted successfully');
    }
}
