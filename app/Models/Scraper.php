<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Model;

class Scraper extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'base_urls',
        'is_debug',
        'with_cache',
        'scrape_limit',
        'sleep',
        'selectors',
    ];

    protected $casts = [
        'base_urls' => 'array',
        'selectors' => 'array',
    ];

    public function runs()
    {
        return $this->hasMany(ScraperRun::class);
    }

    public static function getDefSelectors() {
        return [
            'post' => '',
            'post_link' => '',
            'pagination' => '',
        ];
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                return $user ? '<a href="'.route('admin.users.edit', $user).'">'.$user->name.'</a>' : '';
            })
            ->addColumn('runs', function ($model) {
                return $model->runs()->count();
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'scrapers',
                    'actions' => ['edit', 'show', 'destroy']
                ])->render();
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->adminFormat();
            })
            ->rawColumns(['user', 'action'])
            ->make(true);
    }
}
// $result = \App\Services\PostScraperService::make('https://www.oilmanchina.com/products.html')
//  ->post('.pic-list .item')
//  ->postLink('.title-content a')
//  ->pagination('.cmsmasters_wrap_pagination a')
//  ->value('title', '.cont_r  h2')
//  ->value('images', '#slidePic img', 'src', true)
//  ->value('category', '.aside-list .on a', null, true)
//  ->value('description', '#product_description', 'html')
//  ->value('description-images', '#product_description img', 'src', true)
//  ->value('details1-keys', '.cont_r .p_name', null, true)
//  ->value('details1-values', '.cont_r .p_attribute', null, true)
//  ->value('details2-keys', '#detail_infomation th', null, true)
//  ->value('details2-values', '#detail_infomation td', null, true)
//  ->shot('tables-img', '#product_description table')
//  ->limit($this->scrapeLimit)
//  ->sleep($this->sleep)
//  ->debug($this->scraperDebug)

// {--I|ignore-cache : Ignore cached scraped data. }
// {--D|scraper-debug : Enable scraper logs}
// {--C|cache-file=storage/scraper_jsons/oilmanchina.json : Path to cache file. }
// {--U|user=christal@oilmancn.com : User id or email to which imported posts will be attached. }
// {--scrape-limit=0 : Limit the amount of scraped posts. Scrapping may generate non valid posts, so limiting scraped posts amount not always the same as limiting imported posts amount. }
// {--L|import-limit=0 : Limit the amount of successfully imported posts. }
// {--S|sleep=0 : Wait seconds before scrapping the page. May protect agains 429. }';
