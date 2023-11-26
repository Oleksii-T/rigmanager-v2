<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use App\Services\SubscriptionService;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCycle extends Model
{
    use LogsActivityBasic, \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'subscription_id',
        'invoice',
        'is_active',
        'price',
        'expire_at'
    ];

    protected $casts = [
        'expire_at' => 'datetime',
        'invoice' => 'array'
    ];

    public function user()
    {
        return $this->belongsToThrough(User::class, Subscription::class);
    }

    public function plan()
    {
        return $this->belongsToThrough(SubscriptionPlan::class, Subscription::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function deactivate($expireNow=false, $disablePaidFuntionalities=true)
    {
        $data = ['is_active' => false];

        if ($expireNow) {
            $data['expire_at'] = now();
        }

        $this->update($data);

        if ($disablePaidFuntionalities) {
            SubscriptionService::disablePaidFuntionalities($this->user);
        }
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->editColumn('created_at', function($cycle){
                return $cycle->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->editColumn('expire_at', function($cycle){
                return $cycle->expire_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->editColumn('is_active', function($cycle){
                return $cycle->is_active
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-warning">Inactive</span>';
            })
            ->rawColumns(['is_active'])
            ->make();
    }
}
