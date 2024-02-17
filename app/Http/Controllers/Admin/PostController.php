<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Setting;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Enums\NotificationGroup;
use App\Jobs\MailerProcessNewPost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\PostRequest;

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
        if ($request->group) {
            $posts->where('group', $request->group);
        }
        if ($request->type) {
            $posts->where('posts.type', $request->type);
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
        $input['duration'] = 'unlim';
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
            Notification::make($post->user_id, NotificationGroup::POST_APPROVED, [
                'vars' => [
                    'title' => $post->title
                ]
            ], $post);
            MailerProcessNewPost::dispatch($post);
        } else if ($oldStatus == 'pending' && $post->status == 'rejected' && $hidePending) {
            Notification::make($post->user_id, NotificationGroup::POST_REJECTED, [
                'vars' => [
                    'title' => $post->title
                ]
            ], $post);
        }

        return $this->jsonSuccess('Post updated successfully');
    }

    public function approveAll()
    {
        $posts = Post::where('status', 'pending')->get();

        foreach ($posts as $post) {
            $post->update(['status' => 'approved']);
        }

        return $this->jsonSuccess('Post updated successfully', [
            'reload' => true
        ]);
    }

    public function addViews(Request $request, Post $post)
    {
        for ($i=0; $i < ($request->amount??1); $i++) {
            $view = $post->saveView(true);

            $view->causer_type = null;
            $view->causer_id = null;

            if ($request->date) {
                $date = Carbon::parse($request->date);
                $view->created_at = $date;
                $view->updated_at = $date;
            }

            $view->save();
        }

        return $this->jsonSuccess('Post views added successfully');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return $this->jsonSuccess('Post deleted successfully');
    }
}
