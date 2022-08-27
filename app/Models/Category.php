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
        'is_active'
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
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function name(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function scopeActive($query, bool $is=true)
    {
        $query->where('is_active', $is);
    }

    public static function dataTable($query, $request)
    {
        if ($request->search && $request->search['value']) {
            $value = $request->search['value'];
            $query->whereHas('translations', function ($q) use ($value) {
                $q->where('field', 'name')->where('value', 'like', "%$value%");
            });
        }
        if ($request->parent) {
            $query->where('category_id', $request->parent);
        }
        if ($request->status !== null) {
            $query->where('is_active', (bool)$request->status);
        }
        if ($request->has_childs !== null) {
            if ($request->has_childs) {
                $query->whereHas('childs');
            } else {
                $query->whereDoesntHave('childs');
            }
        }
        if ($request->has_parent !== null) {
            if ($request->has_parent) {
                $query->whereNotNull('category_id');
            } else {
                $query->whereNull('category_id');
            }
        }

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
            ->rawColumns(['parent', 'is_active', 'action'])
            ->make(true);
    }
}
