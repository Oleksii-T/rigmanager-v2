<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Post;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $columns = 3;
        $skip = 0;
        $categoriesAll = Category::whereNull('category_id')->get();
        $perColumn = $categoriesAll->count() / $columns;
        for ($i=1, $skip=0; $i<=$columns; $i++) {
            $take = $i==$columns ? ceil($perColumn) : floor($perColumn);
            $categoriesColumns[] = $categoriesAll->skip($skip)->take($take);
            $skip += $take;
        }
        $newPosts = Post::visible()->latest()->limit(3)->get();
        $urgentPosts = Post::visible()->latest()->where('is_urgent', true)->limit(3)->get();
        $partners = cache()->remember('partners', 60*10, function () {
            return Partner::orderBy('order')->get();
        });

        return view('index', compact('categoriesColumns', 'newPosts', 'urgentPosts', 'partners'));
    }

    public function categories()
    {
        $categories = Category::query()
            ->active()
            ->whereNull('category_id')
            ->with('childs')
            ->get()
            ->sortBy('name');

        return view('categories', compact('categories'));
    }
}
