<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Services\AnalyticsService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\UserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.users.index');
        }

        $users = User::query()
            ->withCount('posts');

        if ($request->role !== null) {
            $users->whereHas('roles', function($q) use ($request){
                $q->where('roles.id', $request->role);
            });
        }

        return User::dataTable($users);
    }

    public function show(Request $request, User $user, AnalyticsService $service)
    {
        $info = $user->info;

        if ($request->table == 'analytics') {
            if ($request->date) {
                $period = explode(' - ', $request->date);
                $from = Carbon::parse($period[0]);
                $to = Carbon::parse($period[1]);
            } else {
                $period = [now()->subMonth(), now()];
            }

            $engagementInfo = $service->engagement($user, $period);
            $info = "$engagementInfo->engagement_points points - {$engagementInfo->engagement_place}st place ($engagementInfo->engagement_percent%)";

            return $this->jsonSuccess('', [
                'info' => $info
            ]);
        } else if ($request->table == 'posts') {
            return \App\Models\Post::dataTable($user->posts());
        } else if ($request->table == 'mailers') {
            return \App\Models\Mailer::dataTable($user->mailers());
        } else if ($request->table == 'subscriptions') {
            return \App\Models\Subscription::dataTable($user->subscriptions());
        } else if ($request->table == 'feedbacks') {
            return \App\Models\Feedback::dataTable($user->feedbacks());
        } else if ($request->table == 'notifications') {
            return \App\Models\Notification::dataTable($user->notifications());
        } else if ($request->table == 'imports') {
            return \App\Models\Import::dataTable($user->imports());
        } else if ($request->table == 'messages') {
            $chats = Message::getChats($user->id);
            return $this->jsonSuccess('', [
                'html' =>  view('admin.messages.table', compact('chats'))->render()
            ]);
        }

        $users = [$user->id, auth()->id()];
        $chat = Message::query()
            ->whereIn('user_id', $users)
            ->whereIn('reciever_id', $users)
            ->oldest()
            ->get();
        $unreadInChat = $chat->where('reciever_id', auth()->id())->where('is_read', false)->count();

        return view('admin.users.show', compact('user', 'chat', 'info', 'unreadInChat'));
    }

    public function getChart(User $user, $type, AnalyticsService $service)
    {
        $result = $service->getChart($type, $user);

        return $this->jsonSuccess('', $result);
    }

    public function login(User $user)
    {
        auth()->login($user);

        return redirect()->route('profile.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $input = $request->validated();
        $userInput = $input['user'];
        $infoInput = $input['info'];

        $userInput['password'] = Hash::make($userInput['password']);
        $user = User::create($userInput);
        $user->addAttachment($userInput['avatar']??null, 'avatar');
        $user->addAttachment($userInput['banner']??null, 'banner');
        $user->roles()->attach($userInput['roles']);

        $infoInput['emails'] = $infoInput['emails'] ? json_decode($infoInput['emails']) : null;
        $infoInput['phones'] = $infoInput['phones'] ? json_decode($infoInput['phones']) : null;
        $user->info()->create($infoInput);

        if ($input['verify_email_now']??false) {
            $user->email_verified_at = now();
            $user->save();
            event(new \Illuminate\Auth\Events\Verified($user));
        } else {
            event(new \Illuminate\Auth\Events\Registered($user));
        }

        return $this->jsonSuccess('User created successfully', [
            'redirect' => route('admin.users.index')
        ]);
    }

    public function edit(User $user)
    {
        $info = $user->info;

        return view('admin.users.edit', compact('user', 'info'));
    }

    public function update(UserRequest $request, User $user)
    {
        $input = $request->validated();
        $userInput = $input['user'];
        $infoInput = $input['info'];

        if ($userInput['password']) {
            $userInput['password'] = Hash::make($userInput['password']);
        } else {
            unset($userInput['password']);
        }

        $user->update($userInput);
        $user->addAttachment($userInput['avatar']??null, 'avatar');
        $user->addAttachment($userInput['banner']??null, 'banner');
        $user->roles()->sync($userInput['roles']??[]);

        $infoInput['emails'] = $infoInput['emails'] ? json_decode($infoInput['emails']) : null;
        $infoInput['phones'] = $infoInput['phones'] ? json_decode($infoInput['phones']) : null;
        $user->info->update($infoInput);

        return $this->jsonSuccess('User updated successfully');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return $this->jsonError('Can not delete current user', [], 200);
        }

        $user->delete();

        return $this->jsonSuccess('User deleted successfully');
    }
}
