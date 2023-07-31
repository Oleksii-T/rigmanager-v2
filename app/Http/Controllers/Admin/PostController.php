<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Setting;
use App\Http\Requests\Admin\PostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Jobs\MailerProcessNewPost;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.posts.index');
        }

        $posts = Post::query();

        if ($request->user_id) {
            $posts->where('user_id', $request->user_id);
        }
        if ($request->category_id) {
            $posts->where('category_id', $request->category_id);
        }
        if ($request->status) {
            $posts->where('status', $request->status);
        }
        if ($request->type) {
            $posts->where('type', $request->type);
        }
        if ($request->condition) {
            $posts->where('condition', $request->condition);
        }
        if ($request->duration) {
            $posts->where('duration', $request->duration);
        }
        if ($request->is_active !== null) {
            $posts->where('is_active', $request->is_active);
        }
        if ($request->is_urgent !== null) {
            $posts->where('is_urgent', $request->is_urgent);
        }
        if ($request->is_import !== null) {
            $posts->where('is_import', $request->is_import);
        }

        return Post::dataTable($posts);
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(PostRequest $request)
    {
        $input = $request->validated();
        $user = User::find($input['user_id']);
        $input['phone'] ??= $user->phone;
        $input['email'] ??= $user->email;
        $post = Post::create($input);
        $post->saveCosts($input);
        $post->saveTranslations($input);
        $post->addAttachment($input['images']??[], 'images');
        $post->addAttachment($input['documents']??[], 'documents');

        return $this->jsonSuccess('Post created successfully', [
            'redirect' => route('admin.posts.index')
        ]);
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $input = $request->validated();
        $input['is_active'] ??= false;
        $input['is_urgent'] ??= false;
        $input['is_import'] ??= false;
        $oldStatus = $post->status;
        $post->update($input);
        $post->saveCosts($input);
        $post->saveTranslations($input);
        $post->addAttachment($input['images']??null, 'images');
        $post->addAttachment($input['documents']??[], 'documents');

        $hidePending = Setting::get('hide_pending_posts', true, true);
        if ($oldStatus == 'pending' && $post->status == 'approved' && $hidePending) {
            MailerProcessNewPost::dispatch($post);
        }

        return $this->jsonSuccess('Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return $this->jsonSuccess('Post deleted successfully');
    }
}
