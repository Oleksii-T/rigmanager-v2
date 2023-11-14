<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translation extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'translatable_id',
        'translatable_type',
        'locale',
        'field',
        'value'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('models')
            ->logAll()
            ->logExcept(['updated_at'])
            ->dontSubmitEmptyLogs();
    }

    public function translatable()
    {
        return $this->morphTo();
    }
}
