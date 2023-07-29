<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class SearchController extends Controller
{
    public function category(Request $request, Category $category)
    {
        $filters = $request->all();
        $filters['category'] = $category;
        $posts = Post::visible()->withCount('views')->filter($filters)->paginate(Post::PER_PAGE);

        if (!$request->ajax()) {
            return view('search', compact('posts', 'category'));
        }

        return $this->jsonSuccess('', [
            'view' => view('components.search.items', ['posts' => $posts])->render(),
            'total' => $posts->total()
        ]);
    }

    public function index(Request $request)
    {
        $posts = Post::visible()->withCount('views')->filter($request->all())->paginate(Post::PER_PAGE);

        if (!$request->ajax()) {
            return view('search', compact('posts'));
        }

        return $this->jsonSuccess('', [
            'view' => view('components.search.items', ['posts' => $posts])->render(),
            'total' => $posts->total()
        ]);
    }
}
