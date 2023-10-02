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
        return new Attribute(
            get: fn () => trans("messages.notifications.{$this->group->name}", $this->data['vars']??[])
        );
    }

    public static function make($uId, $group=null, $data=null, $resource=null, $type=null)
    {
        $group ??= NotificationGroup::MANUAL;
        $type ??= match ($group->value) {
            NotificationGroup::IMPORT_SUCCESS->value => NotificationType::SUCCESS,
            NotificationGroup::IMPORT_FAIL->value => NotificationType::DANGER,
            NotificationGroup::POST_APPROVED->value => NotificationType::SUCCESS,
            NotificationGroup::POST_REJECTED->value => NotificationType::DANGER,
            NotificationGroup::SUB_CREATED->value => NotificationType::SUCCESS,
            NotificationGroup::SUB_CANCELED->value => NotificationType::DANGER,
            NotificationGroup::SUB_END_SOON->value => NotificationType::WARNING,
            NotificationGroup::SUB_ENDED->value => NotificationType::DANGER,
            NotificationGroup::SUB_EXTENDED->value => NotificationType::SUCCESS,
            default => NotificationType::INFO
        };

        return self::create([
            'user_id' => $uId,
            'notifiable_id' => $resource->id??null,
            'notifiable_type' => $resource ? get_class($resource) : null,
            'group' => $group,
            'type' => $type,
            'data' => $data,
        ]);
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
