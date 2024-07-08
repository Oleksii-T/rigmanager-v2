<?php

namespace App\Models;

use App\Enums\PageStatus;
use App\Traits\HasTranslations;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Page extends Model
{
    use HasTranslations;

    protected $fillable = [
        'user_id',
        'status',
        'title',
        'link',
        'template',
        'content',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'status' => PageStatus::class
    ];

    protected $appends = self::TRANSLATABLES + [

    ];

    const TRANSLATABLES = [
        'content',
        'meta_title',
        'meta_description',
    ];

    public function content(): Attribute
    {
        return $this->getTranslatedAttr('content');
    }

    public function metaTitle(): Attribute
    {
        return $this->getTranslatedAttr('meta_title');
    }

    public function metaDescription(): Attribute
    {
        return $this->getTranslatedAttr('meta_description');
    }

    public static function get($url)
    {
        return self::where('link', $url)->first();
    }

    public function items()
    {
        return $this->hasMany(PageItem::class);
    }

    public function isStatic()
    {
        return $this->status == PageStatus::STATIC;
    }

    public function isEntity()
    {
        return $this->status == PageStatus::ENTITY;
    }

    public function notDynamic()
    {
        return $this->isStatic() || $this->isEntity();
    }

    public function show($key, $default='')
    {
        $explode = explode(':', $key);
        $blockName = $explode[0];
        $dataName = $explode[1];
        return $this->pageBlocks
            ->where('name', $blockName)
            ->first()
            ->show($dataName, $default);
    }

    public static function getAllSlugs($forget=false, $formatted=true)
    {
        $cKey = get_class() . '-slugs';

        if ($forget) {
            cache()->forget($cKey);
        }

        $slugs = cache()->remember($cKey, 60*60*24, function () {
            return self::where('status', PageStatus::PUBLISHED)->pluck('link')->toArray();
        });

        if ($formatted) {
            $slugs = '('.implode('|', $slugs).')';
        }

        return $slugs;
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->editColumn('link', function ($model) {
                return $model->status == PageStatus::ENTITY
                    ? $model->link
                    : '<a href="'.url($model->link).'" target="_blank">'.$model->link.'</a>';
            })
            ->editColumn('status', function ($model) {
                return $model->status->readable();
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('admin.pages.actions', compact('model'))->render();
            })
            ->rawColumns(['link', 'action'])
            ->make(true);
    }
}
