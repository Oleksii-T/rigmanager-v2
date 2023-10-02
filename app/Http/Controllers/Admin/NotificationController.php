<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\NotificationRequest;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            $users = User::latest()->get();
            return view('admin.notifications.index', compact('users'));
        }

        $notifications = Notification::query()
            ->latest()
            ->when($request->is_read !== null, function ($q) {
                $q->where('is_read', request()->is_read);
            });

        return Notification::dataTable($notifications);
    }

    public function create(Request $request)
    {
        $users = User::latest()->get();

        return view('admin.notifications.create', compact('users'));
    }

    public function store(NotificationRequest $request)
    {
        $input = $request->validated();
        $input['data'] = json_decode($input['data'], true);
        abort_if(!$input['data'], 401, 'Invalid data json');
        $notification = Notification::create($input);

        return $this->jsonSuccess('Notification created successfully', [
            'redirect' => route('admin.notifications.index')
        ]);
    }

    public function edit(Request $request, Notification $notification)
    {
        $users = User::latest()->get();

        return view('admin.notifications.edit', compact('users', 'notification'));
    }

    public function update(NotificationRequest $request, Notification $notification)
    {
        $input = $request->validated();
        $input['data'] = json_decode($input['data'], true);
        abort_if(!$input['data'], 401, 'Invalid data json');
        $notification->update($input);

        return $this->jsonSuccess('Notification updated successfully', [
            'redirect' => route('admin.notifications.index')
        ]);
    }

    public function destroy(Request $request, Notification $notification)
    {
        $notification->delete();

        return $this->jsonSuccess('Notification deleted successfully');
    }
}
