<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Enums\BlogStatus;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $countries = Blog::pluck('country');
        $tags = Blog::pluck('tags')->flatten();

        if (!$request->ajax()) {
            return view('blogs.index', compact('tags', 'countries'));
        }

        $blogs = Blog::query()
            ->latest('posted_at')
            ->published()
            ->withCount('views')
            ->when($request->tag, function ($q) {
                $q->whereJsonContains('tags', request()->tag);
            })
            ->paginate(10);

        $blogsView = view('blogs.items', compact('blogs'))->render();

        return $this->jsonSuccess('', [
            'blogs' => $blogsView,
            'total' => $blogs->total()
        ]);
    }

    public function show(Request $request, Blog $blog)
    {
        return view('blogs.show', compact('blog'));
    }

    public function view(Request $request, Blog $blog)
    {
        $blog->saveView();
    }
}
