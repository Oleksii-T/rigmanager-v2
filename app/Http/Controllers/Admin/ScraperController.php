<?php

namespace App\Http\Controllers\Admin;

use App\Models\Scraper;
use App\Models\ScraperRun;
use Illuminate\Http\Request;
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
        $selectors = [];
        foreach ($input['selectors']['name'] as $i => $sName) {
            $selectors[$sName] = $input['selectors']['value'][$i];
        }
        $input['base_urls'] = explode(',', $input['base_urls']);
        $input['selectors'] = $selectors;
        $scraper = Scraper::create($input);

        return $this->jsonSuccess('Scraper created successfully', [
            'redirect' => route('admin.scrapers.show', $scraper)
        ]);
    }

    public function show(Request $request, Scraper $scraper)
    {
        if (!$request->ajax()) {
            $run = $scraper->runs()->whereNull('end_at')->first();

            return view('admin.scrapers.show', compact('scraper', 'run'));
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

        $scraper->update($input);
        $scraper->addAttachment($input['image']??null);

        return $this->jsonSuccess('Scraper updated successfully');
    }

    public function destroy(Scraper $scraper)
    {
        $scraper->delete();

        return $this->jsonSuccess('Scraper deleted successfully');
    }
}
