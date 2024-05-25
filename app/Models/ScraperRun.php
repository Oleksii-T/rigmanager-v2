<?php

namespace App\Models;

use App\Enums\ScraperRunStatus;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ScraperRun extends Model
{
    protected $fillable = [
        'scraper_id',
        'status',
        'scraped',
        'saved',
        'max',
        'end_at',
    ];

    protected $casts = [
        'status' => ScraperRunStatus::class,
        'end_at' => 'datetime'
    ];

    public function posts()
    {
        return $this->hasMany(ScraperPost::class, 'run_id');
    }

    public function logs()
    {
        return $this->hasMany(ScraperLog::class, 'run_id');
    }

    public function scraper()
    {
        return $this->belongsTo(Scraper::class);
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            // ->editColumn('status', function ($model) {
            //     return $model->status->readable();
            // })
            // ->addColumn('posts', function ($model) {
            //     return $model->scraped . ' / ' . $model->posts()->count() . ' / ' . $model->max;
            // })
            // ->editColumn('end_at', function ($model) {
            //     return $model->end_at?->adminFormat();
            // })
            // ->editColumn('created_at', function ($model) {
            //     return $model->created_at->adminFormat();
            // })
            // ->addColumn('action', function ($model) {
            //     return 'a';
            // })
            // ->rawColumns(['user', 'action'])
            ->make(true);
    }
}
