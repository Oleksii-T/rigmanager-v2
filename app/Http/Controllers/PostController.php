<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;

class PostController extends Controller
{
    public function show(Request $request, Post $post)
    {

    }

    public function addToFav(Request $request, Post $post)
    {
        $user = auth()->user();
        if (!$user) {
            return $this->jsonError(trans('messages.authError'));
        }
        if ($user->id == $post->user_id) {
            return $this->jsonError(trans('messages.postAddFavPersonal'));
        }
        if ($user->favPosts()->where('posts.id', $post->id)->count()) {
            $user->favPosts()->detach($post);
            return $this->jsonSuccess(trans('messages.postRemovedFav'));
        }

        $user->favPosts()->attach($post);
        return $this->jsonSuccess(trans('messages.postAddedFav'));
    }
}
