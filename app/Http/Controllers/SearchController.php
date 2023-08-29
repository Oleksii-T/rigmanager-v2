<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class SearchController extends Controller
{
    public function index(Request $request, $slug1=null, $slug2=null, $slug3=null)
    {
        $category = $slug3 ?? $slug2 ?? $slug1;
        $filters = $request->all();

        if ($category) {
            $category = Category::getBySlug($category);
            abort_if(!$category, 404);
            $filters['category'] = $category;
        }

        if ($filters['author']??null) {
            $filters['author_name'] = User::where('slug', $filters['author'])->value('name');
        }

        if (!$request->ajax()) {
            return view('search', compact('category', 'filters'));
        }

        $posts = Post::query()
            ->visible()
            ->withCount('views')
            ->filter($filters)
            ->paginate(Post::PER_PAGE);

        $categories = $category
            ? $category->childs()->active()->get()
            : Category::active()->whereNull('category_id')->get();

        $postView = $posts->count()
            ? view('components.search.items', ['posts' => $posts])->render()
            : view('components.search.empty-result')->render();

        return $this->jsonSuccess('', [
            'posts' => $postView,
            'categories' => view('components.search.categories', compact('categories', 'filters'))->render(),
            'total' => $posts->total()
        ]);
    }
}
