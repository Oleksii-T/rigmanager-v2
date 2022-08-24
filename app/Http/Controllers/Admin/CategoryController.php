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

        if ($request->search && $request->search['value']) {
            $value = $request->search['value'];
            $categories->whereHas('translations', function ($q) use ($value) {
                $q->where('field', 'name')->where('value', 'like', "%$value%");
            });
        }
        if ($request->parent) {
            $categories->where('category_id', $request->parent);
        }
        if ($request->status !== null) {
            $categories->where('is_active', (bool)$request->status);
        }
        if ($request->hasChilds !== null) {
            if ($request->hasChilds) {
                $categories->whereHas('childs');
            } else {
                $categories->whereDoesntHave('childs');
            }
        }
        if ($request->hasParent !== null) {
            if ($request->hasParent) {
                $categories->whereNotNull('category_id');
            } else {
                $categories->whereNull('category_id');
            }
        }

        return Category::dataTable($categories);
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

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $input = $request->validated();

        if ($input['password']) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $user->update($input);
        $user->roles()->sync($input['roles']);

        return $this->jsonSuccess('Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return $this->jsonSuccess('Category deleted successfully');
    }
}
