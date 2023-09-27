<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'reciever_id',
        'message',
        'is_read',
    ];

    protected $dispatchesEvents = [
        'saved' => \App\Events\MessageCreated::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reciever()
    {
        return $this->belongsTo(User::class);
    }

    public static function getChatMessages($ids)
    {
        return self::query()
            ->latest()
            ->whereIn('user_id', $ids)
            ->whereIn('reciever_id', $ids);
    }

    public static function dataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user', function ($model) {
                $user = $model->user;
                $reciever = $model->reciever;
                $result = '<a href="'.route('admin.users.edit', $user).'">'.$user->name.'</a>';
                $result .= ' &#x2192; ';
                $result .= '<a href="'.route('admin.users.edit', $reciever).'">'.$reciever->name.'</a>';

                return $result;
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
                return view('admin.messages.actions', compact('model'))->render();
            })
            ->rawColumns(['user', 'is_read', 'action'])
            ->make(true);
    }
}
