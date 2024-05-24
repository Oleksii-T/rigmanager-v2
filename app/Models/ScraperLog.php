<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScraperLog extends Model
{
    protected $fillable = [
        'run_id',
        'parent_id',
        'text',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function run()
    {
        return $this->belongsTo(ScraperRun::class, 'run_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'log_id');
    }
}
