<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use LogsActivityBasic;

    protected $fillable = [
        'user_id',
        'email',
        'name',
        'subject',
        'status',
        'text',
    ];

    protected $casts = [
        'status' => \App\Enums\FeedbackStatus::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromSameIp($asCount=true)
    {
        $ip = $this->created_ip;

        if (!$ip) {
            return null;
        }

        $logs = Activity::query()
            ->where('log_name', 'models')
            ->where('subject_type', self::class)
            ->where('event', 'created')
            ->whereJsonContains('properties->general_info->ip', $ip);

        if ($asCount) {
            return $logs->count();
        }

        $ids = $logs->pluck('subject_id')->toArray();

        return self::whereIn('id', $ids)->get();
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
            ->addColumn('ip', function ($model) {
                $ip = $model->created_ip;
                $count = $model->fromSameIp();

                return "$ip ($count)";
            })
            ->addColumn('action', function ($model) {
                return view('admin.feedbacks.actions', compact('model'))->render();
            })
            ->rawColumns(['user', 'action'])
            ->make(true);
    }
}
