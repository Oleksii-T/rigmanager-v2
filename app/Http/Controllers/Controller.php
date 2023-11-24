<?php

namespace App\Http\Controllers;

use App\Traits\JsonResponsable;
use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, JsonResponsable;

    protected function reachedPostsLimit($user, $fix=0)
    {
        $activePosts = $user->posts()->active()->where('is_trashed', false)->count() + $fix;

        return $activePosts >= SubscriptionPlan::MAX_POSTS_FOR_LEVEL_1 && !$user->isSub(2);
    }
}
