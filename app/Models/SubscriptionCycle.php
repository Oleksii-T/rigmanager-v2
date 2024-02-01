<?php

namespace App\Models;

use Illuminate\Support\Arr;
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

    public static function extractInvoiceData($invoice)
    {
        return [
            'id' => Arr::get($invoice, 'id'),
            'number' => Arr::get($invoice, 'number'),
            'payment_intent_id' => Arr::get($invoice, 'payment_intent.id'),
            'pm_id' => Arr::get($invoice, 'payment_intent.payment_method.id'),
            'brand' => Arr::get($invoice, 'payment_intent.payment_method.card.brand'),
            'last4' => Arr::get($invoice, 'payment_intent.payment_method.card.last4')
        ];
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->editColumn('invoice', function($cycle){
                return json_encode($cycle->invoice);
            })
            ->editColumn('created_at', function($cycle){
                return $cycle->created_at->adminFormat();
            })
            ->editColumn('expire_at', function($cycle){
                return $cycle->expire_at->adminFormat();
            })
            ->editColumn('is_active', function($cycle){
                return $cycle->is_active
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-warning">Inactive</span>';
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'subscription-cycles',
                    'actions' => ['edit']
                ])->render();
            })
            ->rawColumns(['is_active', 'action'])
            ->make();
    }
}
