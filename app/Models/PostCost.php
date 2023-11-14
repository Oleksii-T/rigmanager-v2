<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PostCost extends Model
{
    use LogsActivity;

    protected $fillable = [
        'post_id',
        'is_default',
        'currency',
        'type',
        'cost'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('models')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
