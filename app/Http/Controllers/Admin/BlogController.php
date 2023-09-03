<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.blogs.index');
        }

        $blogs = Blog::query()
            ->orderBy('posted_at')
            ->when($request->status !== null, function ($q) {
                $q->where('status', request()->status);
            });

        return Blog::dataTable($blogs);
    }

    public function create(Request $request)
    {
        $tags = Blog::pluck('tags')->flatten();

        return view('admin.blogs.create', compact('tags'));
    }

    public function store(BlogRequest $request)
    {
        $input = $request->validated();
        $input['posted_at'] = now();
        $blog = Blog::create($input);
        $blog->saveTranslations($input);
        $blog->addAttachment($input['images']??[], 'images');
        $blog->addAttachment($input['documents']??[], 'documents');

        return $this->jsonSuccess('Blog created successfully', [
            'redirect' => route('admin.blogs.index')
        ]);
    }

    public function edit(Request $request, Blog $blog)
    {
        $tags = Blog::pluck('tags')->flatten();

        return view('admin.blogs.edit', compact('blog', 'tags'));
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        $input = $request->validated();
        $input['posted_at'] = Carbon::createFromFormat('Y-m-d H:i', $input['posted_at']);
        $blog->update($input);
        $blog->saveTranslations($input);
        $blog->addAttachment($input['images']??[], 'images');
        $blog->addAttachment($input['documents']??[], 'documents');

        return $this->jsonSuccess('Blog updated successfully', [
            'redirect' => route('admin.blogs.index')
        ]);
    }

    public function destroy(Request $request, Blog $blog)
    {
        $blog->delete();

        return $this->jsonSuccess('Blog updated successfully');
    }
}
