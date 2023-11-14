<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ExchangeRate extends Model
{
    use LogsActivity;

    protected $fillable = [
        'auto_update',
        'from',
        'to',
        'cost'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('models')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public static function convert($from, $to, $amount)
    {
        if ($from == $to) {
            return $amount;
        }

        $all = cache()->remember('axchange-rates', 60*60, function(){
            return self::all();
        });

        $rate = $all->where('from', $from)->where('to', $to)->first();

        if ($rate) {
            return $amount * $rate->cost;
        }

        $rate = $all->where('from', $to)->where('to', $from)->first();

        if ($rate) {
            return $amount * (1/$rate->cost);
        }

        return 1;
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->editColumn('auto_update', function ($model) {
                return $model->auto_update
                    ? '<span class="badge badge-success">yes</span>'
                    : '<span class="badge badge-warning">no</span>';
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'exchange-rates',
                    'actions' => ['edit']
                ])->render();
            })
            ->rawColumns(['auto_update', 'action'])
            ->make(true);
    }
}
