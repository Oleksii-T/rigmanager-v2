<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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

        $categories = $category->childs();

        return Category::dataTable($categories, $request);
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
        $category->delete();

        return $this->jsonSuccess('Category deleted successfully');
    }
}
