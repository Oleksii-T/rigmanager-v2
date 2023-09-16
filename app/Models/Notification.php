<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Yajra\DataTables\DataTables;
use App\Enums\NotificationType;

class Notification extends Model
{
    use HasTranslations;

    const TRANSLATABLES = [
        'text',
    ];

    protected $fillable = [
        'user_id',
        'notifiable_type',
        'notifiable_id',
        'type',
        'is_read',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'type' => NotificationType::class,
    ];

    protected static function boot()
    {
        parent::boot();
        // $model->initTranslations();

        static::deleting(function ($model) {
            $model->purgeTranslations();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function text(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                return '<a href="'.route('admin.users.edit', $user).'">'.$user->name.'</a>';
            })
            ->addColumn('text', function ($model) {
                return $model->text;
            })
            ->editColumn('is_read', function ($model) {
                return $model->is_read
                    ? '<span class="badge badge-success">read</span>'
                    : '<span class="badge badge-warning">pending</span>';
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format(env('ADMIN_DATETIME_FORMAT'));
            })
            ->addColumn('action', function ($model) {
                return view('admin.notifications.actions', compact('model'))->render();
            })
            ->rawColumns(['user', 'is_read', 'action'])
            ->make(true);
    }
}
