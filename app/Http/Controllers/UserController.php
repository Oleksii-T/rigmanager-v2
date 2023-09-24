<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;

class UserController extends Controller
{
    public function show(Request $request, User $user)
    {
        $posts = $user->posts()->latest()->limit(11)->get();
        $totalPosts = $user->posts()->count();

        $categories = Category::query()
            ->whereRelation('posts', 'user_id', $user->id)
            ->withCount(['posts' => function ($q) use ($user) {
                $q->where('user_id', $user->id)->visible();
            }])
            ->orderByDesc('posts_count')
            ->limit(3)
            ->get();

        return view('users.show', compact('user', 'posts', 'categories', 'totalPosts'));
    }

    public function contacts(Request $request, User $user)
    {
        return $this->jsonSuccess('', [
            'phone' => $user->phone ?? trans('ui.notSpecified'),
            'email' => $user->email ?? trans('ui.notSpecified')
        ]);
    }
}
