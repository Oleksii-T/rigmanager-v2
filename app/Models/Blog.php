<?php

namespace App\Models;

use App\Traits\Viewable;
use App\Enums\BlogStatus;
use App\Traits\HasAttachments;
use App\Traits\HasTranslations;
use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Blog extends Model
{
    use HasTranslations, HasAttachments, Viewable, LogsActivityBasic;

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
        'source_name',
        'source_link',
        'country',
        'tags',
        'posted_at'
    ];

    protected $casts = [
        'status' => BlogStatus::class,
        'tags' => 'array',
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
        return $this->morphMany(Attachment::class, 'attachmentable')->where('group', __FUNCTION__)->orderBy('order', 'asc');
    }

    public function thumbnail()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('group', __FUNCTION__);
    }

    public function documents()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')->where('group', __FUNCTION__)->orderBy('order', 'asc');
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

    public function subTitle(): Attribute
    {
        return $this->getTranslatedAttr('sub_title');
    }

    public function metaTitle(): Attribute
    {
        return $this->getTranslatedAttr('meta_title');
    }

    public function metaDescription(): Attribute
    {
        return $this->getTranslatedAttr('meta_description');
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('status', BlogStatus::PUBLISHED);
    }

    public static function dataTable($query)
    {
        $query->withCount('views');
        return DataTables::of($query)
            ->addColumn('thumbnail', function ($model) {
                return '<img src="'.$model->thumbnail->url.'" />';
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
                return $model->posted_at->adminFormat();
            })
            ->addColumn('action', function ($model) {
                return view('admin.blogs.actions', compact('model'))->render();
            })
            ->rawColumns(['thumbnail', 'status', 'action'])
            ->make(true);
    }
}
