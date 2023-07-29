<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCost extends Model
{
    protected $fillable = [
        'post_id',
        'is_default',
        'currency',
        'cost'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
