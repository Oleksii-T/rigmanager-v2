<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Model;

class ScraperLog extends Model
{
    protected $fillable = [
        'run_id',
        'parent_id',
        'text',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function run()
    {
        return $this->belongsTo(ScraperRun::class, 'run_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'log_id');
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->editColumn('text', function ($model) {
                $text = $model->text;
                return view('admin.scraper-logs.text-cell', compact('text'))->render();
            })
            ->editColumn('data', function ($model) {
                $text = json_encode($model->data);
                return view('admin.scraper-logs.text-cell', compact('text'))->render();
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->adminFormat();
            })
            ->rawColumns(['text', 'data'])
            ->make(true);
    }
}
