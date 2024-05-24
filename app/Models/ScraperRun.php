<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Model;

class ScraperRun extends Model
{
    protected $fillable = [
        'scraper_id',
        'end_at',
    ];

    public function posts()
    {
        return $this->hasMany(ScraperPost::class, 'run_id');
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('posts', function ($model) {
                return $model->posts()->count();
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
                    'name' => 'scrapers',
                    'actions' => ['edit', 'show', 'destroy']
                ])->render();
            })
            ->rawColumns(['user', 'action'])
            ->make(true);
    }
}
