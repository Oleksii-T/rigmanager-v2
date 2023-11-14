<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'email',
        'name',
        'subject',
        'status',
        'ip',
        'user_agent',
        'text',
    ];

    protected $casts = [
        'status' => \App\Enums\FeedbackStatus::class
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                if (!$user) {
                    return "$model->name | $model->email";
                }
                return '<a href="'.route('admin.users.edit', $user).'">'.$user->name.'</a>';
            })
            ->editColumn('text', function ($model) {
                return strlen($model->text) > 250
                    ? (substr($model->text, 0, 250) . '...')
                    : $model->text;
            })
            ->editColumn('ip', function ($model) {
                $count = self::where('ip', $model->ip)->count() - 1;
                return "$model->ip ($count)";
            })
            ->addColumn('action', function ($model) {
                return view('admin.feedbacks.actions', compact('model'))->render();
            })
            ->rawColumns(['user', 'action'])
            ->make(true);
    }
}
