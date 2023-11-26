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
        $info = $user->info;
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
        $phones = array_filter($user->info->phones??[]);
        $emails = $user->getEmails();

        activity('users')
            ->event('contacts')
            ->on($user)
            ->tap(function(\Spatie\Activitylog\Contracts\Activity $activity) {
                $activity->properties = infoForActivityLog();
            })
            ->log('');

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
