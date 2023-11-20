<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use LogsActivityBasic;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'stripe_id',
        'status'
    ];

    CONST STATUSES = [
        'pending',
        'active',
        'canceled'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function cycles()
    {
        return $this->hasMany(SubscriptionCycle::class);
    }

    public function cycle()
    {
        return $this->hasOne(SubscriptionCycle::class)->where('is_active', true);
    }

    public function cancel()
    {
        $this->update(['status' => 'canceled']);
    }

    public function isCanceled()
    {
        return $this->status == 'canceled';
    }

    public function isActive()
    {
        return (bool)$this->cycle;
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                return '<a class="btn btn-default" href="' . route('admin.users.edit', $user) . '">' . $user->name . '</a>';
            })
            ->addColumn('plan', function ($model) {
                $plan = $model->plan;
                return '<a class="btn btn-default" href="' . route('admin.subscription-plans.edit', $plan) . '">' . $plan->title . '</a>';
            })
            ->editColumn('status', function ($model) {
                return match ($model->status) {
                    'active'   => '<span class="badge badge-success">active</span>',
                    'canceled' => '<span class="badge badge-danger">canceled</span>',
                    default => '<span class="badge badge-warning">'.$model->status.'</span>',
                };
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'subscriptions',
                    'actions' => ['show', 'destroy']
                ])->render();
            })
            ->rawColumns(['user', 'plan', 'status', 'action'])
            ->make(true);
    }
}
