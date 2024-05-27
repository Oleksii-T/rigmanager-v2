<?php

namespace App\Models;

use App\Enums\ScraperRunStatus;
use App\Enums\ScraperPostStatus;
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
        'max',
        'end_at',
    ];

    protected $casts = [
        'status' => ScraperRunStatus::class,
        'end_at' => 'datetime'
    ];

    public function posts()
    {
        return $this->hasMany(ScraperPost::class, 'run_id')->latest('id');
    }

    public function logs()
    {
        return $this->hasMany(ScraperLog::class, 'run_id');
    }

    public function scraper()
    {
        return $this->belongsTo(Scraper::class);
    }

    public function postToPublish($currentPostId=null)
    {
        $posts = $this->posts()->where('status', ScraperPostStatus::PENDING);

        if (!$currentPostId) {
            return $posts->first();
        }
        
        $posts = $posts->pluck('id')->toArray();

        if (!$posts) {
            return null;
        }

        $i = array_search($currentPostId, $posts);
        $nextPostId = $posts[$i+1] ?? null;

        if (!$nextPostId) {
            $nextPostId = $posts[0];
        }

        return ScraperPost::find($nextPostId);
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->editColumn('status', function ($model) {
                return $model->status->readable();
            })
            ->addColumn('posts', function ($model) {
                return $model->scraped . ' / ' . $model->posts()->count() . ' / ' . $model->max;
            })
            ->editColumn('end_at', function ($model) {
                return $model->end_at?->adminFormat();
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->adminFormat();
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'scraper-runs',
                    'actions' => ['show', 'destroy']
                ])->render();
            })
            ->rawColumns(['user', 'action'])
            ->make(true);
    }
}
