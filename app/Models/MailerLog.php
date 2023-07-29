<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class MailerLog extends Model
{
    protected $fillable = [
        'mailer_id',
        'posts',
        'filters',
    ];

    protected $casts = [
        'posts' => 'array',
        'filters' => 'array'
    ];

    public function mailer()
    {
        return $this->belongsTo(Mailer::class);
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('mailer', function ($model) {
                $mailer = $model->mailer;
                return '<a href="'.route('admin.mailers.edit', $mailer).'">'.$mailer->title.'</a>';
            })
            ->editColumn('posts', function ($model) {
                $posts = Post::whereIn('id', $model->posts)->get();

                return view('components.admin.mailer-logs.posts', compact('posts'))->render();
            })
            ->editColumn('filters', function ($model) {
                $filters = $model->filters;
                return view('components.admin.mailer-logs.filters', compact('filters'))->render();
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->rawColumns(['mailer', 'posts', 'filters'])
            ->make(true);
    }
}
