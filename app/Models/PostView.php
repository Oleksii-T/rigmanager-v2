<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'count',
        'ips'
    ];

    protected $casts = [
        'ips' => 'array'
    ];

    public function user()
    {
        return $this-belongsTo(User::class);
    }

    public function post()
    {
        return $this-belongsTo(Post::class);
    }
}
