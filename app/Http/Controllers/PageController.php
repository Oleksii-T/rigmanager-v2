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
        $categoriesEquipmentColumns = $this->splitToThreeColumns(Category::equipment()->whereNull('category_id')->get());
        $categoriesServiceColumns = $this->splitToThreeColumns(Category::service()->whereNull('category_id')->get());
        $newPosts = Post::equipment()->visible()->latest()->limit(7)->get();
        $urgentPosts = Post::equipment()->visible()->latest()->where('is_urgent', true)->limit(3)->get();
        $partners = cache()->remember('partners', 60*10, function () {
            return Partner::orderBy('order')->get();
        });

        return view('index', compact('categoriesEquipmentColumns', 'categoriesServiceColumns', 'newPosts', 'urgentPosts', 'partners'));
    }

    public function categories($type=null)
    {
        // cache()->forget("categoryes-$type");
        $categories = cache()->remember("categoryes-$type", 60*60*24, function () use ($type) { 
            $isService = $type == 'service';
            $categories = Category::query()
                ->active()
                ->where('type', $isService ? CategoryType::SERVICE : CategoryType::EQUIPMENT)
                ->whereNull('category_id')
                ->with('childs.childs', 'image')
                ->get()
                ->sortBy('name');

            foreach ($categories as &$category) {
                $category->posts_count = $category->postsAll()->visible()->count();
                $category->url = $category->getUrl(false, $isService);
                $category->name = $category->name;
                foreach ($category->childs as &$child) {
                    $child->url = $child->getUrl(false, $isService);
                    $child->name = $child->name;
                    foreach ($child->childs as &$child2) {
                        $child2->url = $child2->getUrl(false, $isService);
                        $child2->name = $child2->name;
                    }
                }
            }

            return $categories;
        });
        $view = $type == 'service' ? 'categories-services' : 'categories';

        return view($view, compact('categories'));
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

    private function splitToThreeColumns($collection)
    {
        $size = ceil($collection->count() / 3);
        $firstCollection = $collection->splice(0, $size);
        $secondCollection = $collection->splice(0, $size);
        $thirdCollection = $collection;

        return [$firstCollection, $secondCollection, $thirdCollection];
    }
}
