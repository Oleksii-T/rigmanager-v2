<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mailer extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'is_active',
        'title',
        'slug',
        'filters',
        'posts',
        'to_mail',
        'last_at'
    ];

    protected $casts = [
        'filters' => 'array',
        'posts' => 'array',
        'last_at' => 'datetime',
        'to_mail' => 'array'
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

    // Get the route key for the model.
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFilter($fName, $asModel=false)
    {
        $filter = $this->filters[$fName] ?? null;

        if ($asModel && $filter) {
            if ($fName == 'author') {
                $filter = User::find($filter);
            } else if ($fName == 'category') {
                $filter = Category::find($filter);
            }
        }

        return $filter;
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                return '<a href="'.route('admin.users.edit', $user).'">'.$user->name.'</a>';
            })
            ->editColumn('is_active', function ($model) {
                return $model->is_active
                    ? '<span class="badge badge-success">yes</span>'
                    : '<span class="badge badge-warning">no</span>';
            })
            ->addColumn('posts', function ($model) {
                return count($model->posts??[]);
            })
            ->editColumn('last_at', function ($model) {
                return $model->last_at?->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('components.admin.actions', [
                    'model' => $model,
                    'name' => 'mailers'
                ])->render();
            })
            ->rawColumns(['user', 'category', 'is_active', 'action'])
            ->make(true);
    }
}
