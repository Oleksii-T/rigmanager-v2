<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScraperPost extends Model
{
    protected $fillable = [
        'run_id',
        'data'
    ];
    protected $casts = [
        'data' => 'array'
    ];

    public function run()
    {
        return $this->belongsTo(ScraperRun::class, 'run_id');
    }
}
