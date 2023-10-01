<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Yajra\DataTables\DataTables;
use App\Enums\NotificationType;
use App\Enums\NotificationGroup;
use Illuminate\Database\Eloquent\Builder;

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
        'group',
        'is_read',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'type' => NotificationType::class,
        'group' => NotificationGroup::class,
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

    public function scopeRead(Builder $query, bool $is=true)
    {
        return $query->where('is_read', $is);
    }

    public function text(): Attribute
    {
        return $this->getTranslatedAttr(__FUNCTION__);
    }

    // get default type for group
    public static function groupToType($group)
    {
        $map = [
            NotificationGroup::MANUAL => NotificationType::INFO,
            NotificationGroup::DAILY_POSTS_VIEWS => NotificationType::INFO,
            NotificationGroup::DAILY_CONTACS_SHOWS => NotificationType::INFO,
            NotificationGroup::WEEKLY_POSTS_VIEWS => NotificationType::INFO,
            NotificationGroup::WEEKLY_CONTACS_SHOWS => NotificationType::INFO,
            NotificationGroup::MAILER_SEND => NotificationType::INFO,
            NotificationGroup::IMPORT_SUCCESS => NotificationType::SUCCESS,
            NotificationGroup::IMPORT_FAIL => NotificationType::DANGER,
            NotificationGroup::POST_APPROVED => NotificationType::SUCCESS,
            NotificationGroup::POST_REJECTED => NotificationType::DANGER,
            NotificationGroup::SUB_CREATED => NotificationType::SUCCESS,
            NotificationGroup::SUB_CANCELED => NotificationType::DANGER,
            NotificationGroup::SUB_END_SOON => NotificationType::WARNING,
            NotificationGroup::SUB_ENDED => NotificationType::DANGER,
            NotificationGroup::SUB_EXTENDED => NotificationType::SUCCESS,
        ];

        return $map[$group];
    }

    public static function groupText($group)
    {
        $map = [
            NotificationGroup::DAILY_POSTS_VIEWS => trans('messages.notifications.'),
            NotificationGroup::DAILY_CONTACS_SHOWS => trans('messages.notifications.'),
            NotificationGroup::WEEKLY_POSTS_VIEWS => trans('messages.notifications.'),
            NotificationGroup::WEEKLY_CONTACS_SHOWS => trans('messages.notifications.'),
            NotificationGroup::MAILER_SEND => trans('messages.notifications.'),
            NotificationGroup::IMPORT_SUCCESS => trans('messages.notifications.'),
            NotificationGroup::IMPORT_FAIL => trans('messages.notifications.'),
            NotificationGroup::POST_APPROVED => trans('messages.notifications.'),
            NotificationGroup::POST_REJECTED => trans('messages.notifications.'),
            NotificationGroup::SUB_CREATED => trans('messages.notifications.'),
            NotificationGroup::SUB_CANCELED => trans('messages.notifications.'),
            NotificationGroup::SUB_END_SOON => trans('messages.notifications.'),
            NotificationGroup::SUB_ENDED => trans('messages.notifications.'),
            NotificationGroup::SUB_EXTENDED => trans('messages.notifications.'),
        ];

        return $map[$group];
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
                    : '<span class="badge badge-warning">unread</span>';
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
