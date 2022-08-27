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
            'users_total' => User::count(),
            'posts_total' => Post::count(),
        ];

        return view('admin.index', compact('data'));
    }
}
