<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;
use App\Traits\HasTranslations;
use App\Traits\HasAttachments;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    use HasFactory, HasTranslations, HasAttachments;

    protected $fillable = [
        'user_id',
        'category_id',
        'origin_lang',
        'status',
        'type',
        'condition',
        'duration',
        'is_active',
        'is_urgent',
        'is_import',
        'amount',
        'location',
        'manufacturer',
        'manufacture_date',
        'part_number',
        'cost',
        'currency',
        'cost_usd'
    ];

    protected $appends = self::TRANSLATABLES + [

    ];

    const STATUSES = [
        'approved',
        'pending',
        'draft',
        'banned'
    ];

    const DURATIONS = [
        '1m',
        '2m',
        'unlim'
    ];

    const TYPES = [
        'sell',
        'buy',
        'rent',
        'lease'
    ];

    const CONDITIONS = [
        'none',
        'new',
        'used',
        'for-parts'
    ];

    CONST LEGAL_TYPES = [
        'private',
        'business'
    ];

    const TRANSLATABLES = [
        'title',
        'description',
        'slug'
    ];

    const PER_PAGE = 25;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->purgeFiles();
            $model->purgeTranslations();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function views()
    {
        return $this->hasMany(PostView::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')->where('group', 'images');
    }

    public function documents()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')->where('group', 'documents');
    }

    public function scopeActive($query, bool $is=true)
    {
        return $query->where('is_active', $is);
    }

    public function scopeVisible($query)
    {
        if (Setting::get('hide_pending_posts')) {
            return $query->where('is_active', true);
        }
        return $query->where('is_active', true)->where('status', 'approved');
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function slug(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function title(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function description(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public function costReadable(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->cost ? ('$' . number_format($this->cost, 2)) : null,
        );
    }

    public function thumbnail()
    {
        // TODO make login
        return $this->images()->first();
    }

    public function original($field)
    {
        return $this->translated($field, $this->origin_lang);
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                return '<a href="'.route('admin.users.edit', $user).'">'.$user->name.'</a>';
            })
            ->addColumn('category', function ($model) {
                $c = $model->category;
                return '<a href="'.route('admin.categories.edit', $c).'">'.$c->name.'</a>';
            })
            ->editColumn('is_active', function ($model) {
                return $model->is_active
                    ? '<span class="badge badge-success">yes</span>'
                    : '<span class="badge badge-warning">no</span>';
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'posts'
                ])->render();
            })
            ->rawColumns(['user', 'category', 'is_active', 'action'])
            ->make(true);
    }

    public static function typeReadable($type)
    {
        switch ($type) {
            case 'sell':
                return trans('posts.types.sell');
            case 'buy':
                return trans('posts.types.buy');
            case 'rent':
                return trans('posts.types.rent');
            case 'lease':
                return trans('posts.types.lease');
        }
    }

    public static function conditionReadable($condition)
    {
        switch ($condition) {
            case 'none':
                return trans('posts.conditions.none');
            case 'new':
                return trans('posts.conditions.new');
            case 'used':
                return trans('posts.conditions.used');
            case 'for-parts':
                return trans('posts.conditions.for-parts');
        }
    }

    public static function legalTypeReadable($legalType)
    {
        switch ($legalType) {
            case 'private':
                return trans('posts.legal-types.private');
            case 'business':
                return trans('posts.legal-types.business');
        }
    }

    public static function durationReadable($duration)
    {
        switch ($duration) {
            case '1m':
                return trans('ui.activeOneMonth');
            case '2m':
                return trans('ui.activeTwoMonth');
            case 'unlim':
                return trans('ui.activeForever');
        }
    }
}
