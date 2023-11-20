<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SubscriptionPlan extends Model
{
    use HasTranslations, LogsActivityBasic;

    CONST INTERVALS = [
        'month',
        'year'
    ];

    const TRANSLATABLES = [
        'title',
        'description',
        'slug'
    ];

    protected $fillable = [
        'stripe_id',
        'price',
        'interval',
        'trial'
    ];

    protected $appends = self::TRANSLATABLES + [

    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->purgeTranslations();
        });
    }

    // overload laravel`s method for route key generation
    public function getRouteKey()
    {
        $s = $this->slug;

        return $s;
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

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->editColumn('price', function ($model) {
                return Setting::get('currency_sign') . $model->price;
            })
            ->editColumn('interval', function ($model) {
                return ucfirst($model->interval);
            })
            ->editColumn('trial', function ($model) {
                return $model->trial ? ($model->trial . ' days') : 'none';
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'subscription-plans'
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
