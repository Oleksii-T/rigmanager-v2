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
        if (isset($request->text)) {
            // todo
        } else if (isset($request->author)){
            $user = User::where('slug', $request->author)->firstOrFail();
            $posts->where('user_id', $user->id);
        } else if (isset($request->type)) {
            $posts->where('type', $request->type);
        }

        Post::applyFilters($posts, $request->all());

        // $currency = 'uah';
        // $costFrom = 4000;
        // $posts->whereHas('costs')->leftJoin('post_costs', function ($join) use ($currency) {
        //     $join->on('posts.id', '=', 'post_costs.post_id');
        //     $join->on('currency', '=', \DB::raw("'$currency'"));
        // })
        // ->orderBy('post_costs.cost')
        // ->orderByDesc('post_costs.cost')
        // ->select('posts.*', 'post_costs.cost', 'post_costs.currency')
        // ;
        // $post = $posts->first();
        // dump($post->cost);
        // dd($post);


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
