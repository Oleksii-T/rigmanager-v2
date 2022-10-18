<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class Feedback extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'name',
        'subject',
        'text',
        'is_read',
    ];

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
            ->addColumn('action', function ($model) {
                return view('admin.feedbacks.actions', compact('model'))->render();
            })
            ->rawColumns(['user', 'action'])
            ->make(true);
    }
}
