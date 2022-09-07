<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class SearchController extends Controller
{
    public function category(Request $request, Category $category)
    {
        $posts = $category->postsAll()->visible()->withCount('views');
        $posts = $this->filter($posts)->paginate(Post::PER_PAGE);

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
        $posts = Post::latest()->visible();
        if (isset($request->text)) {
            // todo
        } else if (isset($request->author)){
            $user = User::where('slug', $request->author)->firstOrFail();
            $posts->where('user_id', $user->id);
        } else if (isset($request->type)) {
            $posts->where('type', $request->type);
        }

        $posts = $posts->paginate(Post::PER_PAGE);

        return view('search', compact('posts'));
    }

    private function helper($posts, $request)
    {
        $posts->visible()->withCount('views');
        $posts = $this->filter($posts)->paginate(Post::PER_PAGE);

        if (!$request->ajax()) {
            return view('search', compact('posts'));
        }

        return $this->jsonSuccess('', [
            'view' => view('components.search.items', ['posts' => $posts])->render(),
            'total' => $posts->total()
        ]);
    }

    private function filter($posts)
    {
        $costFrom = request()->cost_from;
        $costTo = request()->cost_to;
        $curreny = request()->currency;
        $conditions = request()->conditions;
        $types = request()->types;
        $legalTypes = request()->legal_types;
        $urgent = request()->is_urgent[0]??null;
        $import = request()->is_import[0]??null;
        $sort = request()->sorting;

        if ($conditions && count($conditions) < count(Post::CONDITIONS)) {
            $posts->whereIn('condition', $conditions);
        }

        if ($types && count($types) < count(Post::TYPES)) {
            $posts->whereIn('type', $types);
        }

        if ($urgent !== null) {
            $posts->where('is_urgent', $urgent);
        }

        if ($import !== null) {
            $posts->where('is_import', $import);
        }

        switch ($sort) {
            case 'latest':
                $posts->latest();
                break;
            case 'cheapest':
                $posts->whereNotNull('cost')->orderBy('cost_usd');
                break;
            case 'expensive':
                $posts->whereNotNull('cost')->orderByDesc('cost_usd');
                break;
            case 'popular':
                $posts->orderByDesc('views_count');
                break;
            default:
                $posts->latest();
                break;
        }

        return $posts;
    }
}
