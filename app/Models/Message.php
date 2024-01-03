<?php

namespace App\Models;

use Yajra\DataTables\DataTables;
use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use LogsActivityBasic;

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

    public static function getChats($userId)
    {
        $chats = [];
        $chatCodes = [];

        if ($userId)  {
            $chatCodesRaw = Message::query()
                ->where('user_id', $userId)
                ->orWhere('reciever_id', $userId)
                ->select('user_id', 'reciever_id')
                ->get();
        } else {
            $chatCodesRaw = Message::query()
                ->distinct()
                ->select('user_id', 'reciever_id')
                ->get();
        }

        foreach ($chatCodesRaw as $chatRaw) {
            $ids = [$chatRaw->user_id, $chatRaw->reciever_id];
            $code = implode('-', $ids);
            $code2 = implode('-', array_reverse($ids));

            if (in_array($code, $chatCodes) || in_array($code2, $chatCodes)) {
                continue;
            }

            $chatCodes[] = $code;
            $chatMessages = Message::getChatMessages($ids)->get();
            $chats[] = [
                'uids' => $ids,
                'users' => User::whereIn('id', $ids)->get(),
                'count' => $chatMessages->count(),
                'unread' => $chatMessages->where('is_read', false)->count(),
                'last_message' => $chatMessages->first()->message,
                'last_at' => $chatMessages->first()->created_at,
            ];
        }

        return $chats;
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
                return $model->created_at->adminFormat();
            })
            ->addColumn('action', function ($model) {
                return view('admin.messages.actions', compact('model'))->render();
            })
            ->rawColumns(['user', 'is_read', 'action'])
            ->make(true);
    }
}
