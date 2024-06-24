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
        'exclude_urls',
        'sleep',
        'selectors',
        'post_selector',
        'post_link_selector',
        'pagination_selector',
        'category_selector'
    ];

    protected $casts = [
        'base_urls' => 'array',
        'selectors' => 'array',
        'exclude_urls' => 'array',
    ];

    public function runs()
    {
        return $this->hasMany(ScraperRun::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
