<?php

namespace App\Models;

use App\Enums\ScraperPostStatus;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Model;

class ScraperPost extends Model
{
    protected $fillable = [
        'run_id',
        'status',
        'url',
        'data'
    ];
    protected $casts = [
        'data' => 'array',
        'status' => ScraperPostStatus::class
    ];

    public function run()
    {
        return $this->belongsTo(ScraperRun::class, 'run_id');
    }

    public function statusClass()
    {
        return match ($this->status->value) {
            ScraperPostStatus::PENDING->value => 'text-warning',
            ScraperPostStatus::PUBLISHED->value => 'text-success',
            default => 'text-danger'
        };
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->editColumn('url', function ($model) {
                $status = $model->statusClass();
                $url = $model->url;
                return "<a href='$url' class='$status' target='_blank'>$url</a>";
            })
            ->editColumn('data', function ($model) {
                return view('admin.scraper-posts.data-cell', ['data' => $model->data]);
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->adminFormat();
            })
            ->rawColumns(['url', 'text', 'data'])
            ->make(true);
    }
}
