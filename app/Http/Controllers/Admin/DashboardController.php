<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'users' => User::latest()->limit(5)->get(),
            'users_total' => User::count(),
            'posts' => Post::latest()->limit(5)->get(),
            'posts_total' => Post::count(),
        ];

        return view('admin.index', compact('data'));
    }
}
