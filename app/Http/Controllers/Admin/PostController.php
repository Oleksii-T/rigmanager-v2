<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\Admin\PostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.posts.index');
        }

        $posts = Post::query();

        return Post::dataTable($posts);
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(PostRequest $request)
    {
        $input = $request->validated();
        $input['password'] = Hash::make($input['password']);
        $user = Post::create($input);
        $user->roles()->attach($input['roles']);

        return $this->jsonSuccess('Post created successfully', [
            'redirect' => route('admin.posts.index')
        ]);
    }

    public function edit(Post $user)
    {
        return view('admin.posts.edit', compact('user'));
    }

    public function update(PostRequest $request, Post $user)
    {
        $input = $request->validated();

        if ($input['password']) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $user->update($input);
        $user->roles()->sync($input['roles']);

        return $this->jsonSuccess('Post updated successfully');
    }

    public function destroy(Post $user)
    {
        if ($user->id == auth()->id()) {
            return $this->jsonError('Can not delete current user', [], 200);
        }

        $user->delete();

        return $this->jsonSuccess('Post deleted successfully');
    }
}
