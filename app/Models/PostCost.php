<?php

namespace App\Models;

use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;

class PostCost extends Model
{
    use LogsActivityBasic;

    protected $fillable = [
        'post_id',
        'is_default',
        'currency',
        'type',
        'cost'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
