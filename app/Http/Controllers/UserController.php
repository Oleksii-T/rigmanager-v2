<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;

class UserController extends Controller
{
    public function show(Request $request, User $user)
    {
        $currentUser = auth()->user();
        $info = $currentUser->info;
        $hasChat = $currentUser?->hasChatWith($user->id);
        $posts = $user->posts()->visible()->latest()->limit(11)->get();
        $totalPosts = $user->posts()->visible()->count();

        $categories = Category::query()
            ->whereRelation('posts', 'user_id', $user->id)
            ->withCount(['posts' => function ($q) use ($user) {
                $q->where('user_id', $user->id)->visible();
            }])
            ->orderByDesc('posts_count')
            ->limit(3)
            ->get();

        return view('users.show', compact('user', 'info', 'posts', 'categories', 'totalPosts', 'hasChat'));
    }

    public function contacts(Request $request, User $user)
    {
        $phones = $user->info->phones??[];
        $phones = array_filter($phones);
        $emails = $user->info->emails??[];
        $emails = array_filter($emails);
        if (!$emails) {
            $emails = [$user->email];
        }

        return $this->jsonSuccess('', [
            'emails' => $emails,
            'phones' => $phones ? $phones : trans('ui.notSpecified'),
        ]);
    }

    public function view(Request $request, User $user)
    {
        $user->saveView();
    }
}
