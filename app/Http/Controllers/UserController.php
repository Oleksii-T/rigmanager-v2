<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show(Request $request, User $user)
    {
        $posts = $user->posts()->latest()->limit(11)->get();
        $totalPosts = $user->posts()->count();
        $categories = [];

        return view('users.show', compact('user', 'posts', 'categories', 'totalPosts'));
    }
}
