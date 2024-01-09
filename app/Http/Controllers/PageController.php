<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Post;
use App\Models\Partner;
use App\Models\Category;
use App\Enums\UserStatus;
use App\Enums\CategoryType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $columns = 3;
        $skip = 0;
        $categoriesAll = Category::equipment()->whereNull('category_id')->get();
        $perColumn = $categoriesAll->count() / $columns;
        for ($i=1, $skip=0; $i<=$columns; $i++) {
            $take = $i==$columns ? ceil($perColumn) : floor($perColumn);
            $categoriesColumns[] = $categoriesAll->skip($skip)->take($take);
            $skip += $take;
        }
        $newPosts = Post::equipment()->visible()->latest()->limit(7)->get();
        $urgentPosts = Post::equipment()->visible()->latest()->where('is_urgent', true)->limit(3)->get();
        $partners = cache()->remember('partners', 60*10, function () {
            return Partner::orderBy('order')->get();
        });

        return view('index', compact('categoriesColumns', 'newPosts', 'urgentPosts', 'partners'));
    }

    public function categories($type=null)
    {
        $categories = Category::query()
            ->active()
            ->where('type', $type == 'service' ? CategoryType::SERVICE : CategoryType::EQUIPMENT)
            ->whereNull('category_id')
            ->with('childs')
            ->get()
            ->sortBy('name');

        return view('categories', compact('categories'));
    }

    public function about()
    {
        return view('about');
    }

    public function faq()
    {
        $faqs = cache()->remember('global.faqs', 60*60*24, function () {
            return Faq::orderBy('order')->get();
        });

        return view('faq', compact('faqs'));
    }

    public function terms()
    {
        return view('terms');
    }

    public function siteMap()
    {
        return view('sitemap');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function pageAssistShown(Request $request)
    {
        activity('page-assists')
            ->event($request->type)
            ->withProperties(infoForActivityLog())
            ->log('');
    }

    public function banned()
    {
        $user = auth()->user();

        if ($user->status != UserStatus::BANNED) {
            return redirect()->route('index');
        }

        return view('banned');
    }
}
