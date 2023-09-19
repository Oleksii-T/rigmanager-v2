<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'reciever_id',
        'message',
        'is_seen',
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
}
