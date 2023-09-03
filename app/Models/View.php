<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = [
        'user_id',
        'viewable_type',
        'viewable_id',
        'count',
        'ips'
    ];

    protected $casts = [
        'ips' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function viewable()
    {
        return $this->morphTo('viewable');
    }
}
