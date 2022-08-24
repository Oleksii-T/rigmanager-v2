<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;
use App\Traits\HasTranslations;
use App\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Category extends Model
{
    use HasTranslations, HasAttachments;

    protected $fillable = [
        'category_id',
        'ident'
    ];

    protected $appends = self::TRANSLATABLES + [

    ];

    const TRANSLATABLES = [
        'name',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->purgeFiles();
            $model->purgeTranslations();
        });
    }

    public function image()
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'category_id');
    }

    public function slug(): Attribute
    {
        return new Attribute(
            get: fn () => $this->translated('slug')
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn () => $this->translated('name')
        );
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('parent', function ($model) {
                $parent = $model->parent;
                if ($parent) {
                    return '<a href="'.route('admin.categories.edit', $parent).'">'.$parent->name.'</a>';
                }
                return '';
            })
            ->editColumn('is_active', function ($model) {
                return $model->is_active
                    ? '<span class="badge badge-success">yes</span>'
                    : '<span class="badge badge-warning">no</span>';
            })
            ->addColumn('childs', function ($model) {
                return $model->childs()->count();
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'categories'
                ])->render();
            })
            ->filter(function ($query) {

            }, true)
            ->rawColumns(['parent', 'is_active', 'action'])
            ->make(true);
    }
}
