<?php

namespace App\Models;

use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translation extends Model
{
    use HasFactory, LogsActivityBasic;

    protected $fillable = [
        'translatable_id',
        'translatable_type',
        'locale',
        'field',
        'value'
    ];

    public function translatable()
    {
        return $this->morphTo();
    }
}
