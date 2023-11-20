<?php

namespace App\Models;

use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use LogsActivityBasic;

    protected $fillable = [
        'user_id',
        'is_default',
        'token',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
