<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use App\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Yajra\DataTables\DataTables;
use App\Enums\BlogStatus;

class Blog extends Model
{
    use HasTranslations, HasAttachments;

    const TRANSLATABLES = [
        'title',
        'sub_title',
        'body',
        'slug',
        'meta_title',
        'meta_description',
    ];

    protected $fillable = [
        'status',
        'posted_at'
    ];

    protected $casts = [
        'status' => BlogStatus::class,
        'posted_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        // $model->initTranslations();

        static::deleting(function ($model) {
            $model->purgeAttachments();
            $model->purgeTranslations();
        });
    }

    // overload laravel`s method for route key generation
    public function getRouteKey()
    {
        return $this->slug;
    }

    public function images()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')->where('group', 'images')->orderBy('order', 'asc');
    }

    public function documents()
    {
        return $this->morphMany(Attachment::class, 'documents')->where('group', 'documents')->orderBy('order', 'asc');
    }

    public function slug(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function title(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function body(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function sub_title(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function thumbnail()
    {
        $img = $this->images->first();

        return $img;
    }

    // public static function allSlugs($ignore=null)
    // {
    //     return Translation::query()
    //         ->where('translatable_type', self::class)
    //         ->where('translatable_id', '!=', $ignore)
    //         ->where('field', 'slug')
    //         ->pluck('value')
    //         ->toArray();
    // }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('thumbnail', function ($model) {
                return '<img src="'.$model->thumbnail()->url.'" />';
            })
            ->addColumn('title', function ($model) {
                return $model->title;
            })
            ->editColumn('status', function ($model) {
                return match ($model->status) {
                    BlogStatus::DRAFT => '<span class="badge badge-warning">draft</span>',
                    BlogStatus::PUBLISHED => '<span class="badge badge-success">published</span>',
                    BlogStatus::TRASHED => '<span class="badge badge-danger">trashed</span>'
                };
            })
            ->editColumn('posted_at', function ($model) {
                return $model->posted_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('admin.blogs.actions', compact('model'))->render();
            })
            ->rawColumns(['thumbnail', 'status', 'action'])
            ->make(true);
    }
}
