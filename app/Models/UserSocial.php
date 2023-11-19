<?php

namespace App\Models;

use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Model;

class UserSocial extends Model
{
    use LogsActivityBasic;

    protected $fillable = [
        'user_id',
        'provider',
        'token'
    ];
}
