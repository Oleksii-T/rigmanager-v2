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

        return Category::dataTable($categories);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $input = $request->validated();
        $input['password'] = Hash::make($input['password']);
        $user = Category::create($input);
        $user->roles()->attach($input['roles']);

        return $this->jsonSuccess('Category created successfully', [
            'redirect' => route('admin.categories.index')
        ]);
    }

    public function edit(Category $user)
    {
        return view('admin.categories.edit', compact('user'));
    }

    public function update(CategoryRequest $request, Category $user)
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

    public function destroy(Category $user)
    {
        if ($user->id == auth()->id()) {
            return $this->jsonError('Can not delete current user', [], 200);
        }

        $user->delete();

        return $this->jsonSuccess('Category deleted successfully');
    }
}
