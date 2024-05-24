<?php

namespace App\Http\Controllers\Admin;

use App\Models\Scraper;
use App\Models\ScraperRun;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScraperRequest;

class ScraperRunController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->validate([
            'scraper_id' => ['required', 'exists:scrapers,id']
        ]);
        $scraper = ScraperRun::create($input);

        return $this->jsonSuccess('Scraper run created successfully', [
            'redirect' => route('admin.scrapers.show', $scraper)
        ]);
    }
}
