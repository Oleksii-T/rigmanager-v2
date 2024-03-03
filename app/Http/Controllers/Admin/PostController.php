<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
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

        $posts = $this->filter($request->all(), Post::query());

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

    public function edit(Request $request, Post $post)
    {
        $approveFilters = $request->approveFilters;
        $approvingPosts = null;

        if ($approveFilters) {
            $approveFilters = json_decode($approveFilters, true);
            $approvingPosts = $this->filter($approveFilters, Post::query())->latest()->get();
        }

        return view('admin.posts.edit', compact('post', 'approvingPosts'));
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

        if ($request->approveFilters) {
            $f = json_decode($request->approveFilters, true);
            $post = $this->filter($f, Post::query())
                ->latest()
                ->where('id', '<', $post->id)
                ->first();

            if (!$post) {
                return $this->jsonSuccess('Post updated. Next post to apprive not found');
            }

            return $this->jsonSuccess('Post updated successfully', [
                'redirect' => route('admin.posts.edit', $post) . '?approveFilters=' . $request->approveFilters
            ]);
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

    public function startApproving(Request $request)
    {
        $post = $this->filter($request->filters, Post::query())->latest()->first();

        if (!$post) {
            return $this->jsonError('No posts to approve found');
        }

        return $this->jsonSuccess('', [
            'redirect' => route('admin.posts.edit', $post) . '?approveFilters=' . json_encode($request->filters)
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

    private function filter($f, $p)
    {
        $p
            ->when($f['user_id']??false, fn ($q) => $q->where('user_id', $f['user_id']))
            ->when($f['category_id']??false, fn ($q) => $q->where('category_id', $f['category_id']))
            ->when($f['status']??false, fn ($q) => $q->where('status', $f['status']))
            ->when($f['group']??false, fn ($q) => $q->where('group', $f['group']))
            ->when($f['type']??false, fn ($q) => $q->where('posts.type', $f['type']))
            ->when($f['condition']??false, fn ($q) => $q->where('condition', $f['condition']))
            ->when($f['duration']??false, fn ($q) => $q->where('duration', $f['duration']))
            ->when(($f['is_active']??null) !== null, fn ($q) => $q->where('is_active', $f['is_active']))
            ->when(($f['is_urgent']??null) !== null, fn ($q) => $q->where('is_urgent', $f['is_urgent']))
            ->when(($f['is_import']??null) !== null, fn ($q) => $q->where('is_import', $f['is_import']));

        return $p;
    }
}
