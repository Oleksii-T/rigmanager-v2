<?php

namespace App\Models;

use App\Traits\LogsActivityBasic;
use Illuminate\Database\Eloquent\Relations\Pivot;

final class UserFavPost extends Pivot
{
    use LogsActivityBasic;

    protected $table = 'user_fav_posts';
}
