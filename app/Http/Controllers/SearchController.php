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
        $posts = Post::visible()->withCount('views');
        $filters = $request->all();
        $filters['category'] = $category;
        Post::applyFilters($posts, $filters);
        $posts = $posts->paginate(Post::PER_PAGE);

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
        $posts = Post::visible()->withCount('views');

        Post::applyFilters($posts, $request->all());
        $posts = $posts->paginate(Post::PER_PAGE);

        if (!$request->ajax()) {
            return view('search', compact('posts'));
        }

        return $this->jsonSuccess('', [
            'view' => view('components.search.items', ['posts' => $posts])->render(),
            'total' => $posts->total()
        ]);
    }
}
