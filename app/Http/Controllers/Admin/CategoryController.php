<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.categories.index');
        }

        $categories = Category::query();

        return Category::dataTable($categories, $request);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $input = $request->validated();
        $category = Category::create($input);
        $category->saveTranslations($input);
        $category->addAttachment($input['image']);

        return $this->jsonSuccess('Category created successfully', [
            'redirect' => route('admin.categories.index')
        ]);
    }

    public function edit(Request $request, Category $category)
    {
        if (!$request->ajax()) {
            return view('admin.categories.edit', compact('category'));
        }

        if ($request->has('parent')) {

            $categories = $category->childs();

            return Category::dataTable($categories, $request);
        }

        $posts = $category->postsAll();

        return Post::dataTable($posts, $request);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $input = $request->validated();
        $category->update($input);
        $category->saveTranslations($input);
        $category->addAttachment($input['image']??null);

        return $this->jsonSuccess('Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $parents = $category->parents();
        foreach ($parents as $parent) {
            if ($parent->id != $category->id && $parent->is_active) {
                break;
            }
        }
        if (!$parent) {
            $parent = Category::getDefault();
        }
        foreach ($category->postsAll(true) as $post) {
            $post->update([
                'category_id' => $parent->id
            ]);
        }

        $category->delete();

        return $this->jsonSuccess('Category deleted successfully');
    }
}
