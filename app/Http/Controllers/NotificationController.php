<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(10);

        if (!$request->ajax()) {
            return view('profile.notifications', compact('notifications'));
        }

        return $this->jsonSuccess('', [
            'html' => view('components.profile.notifications-table', compact('notifications'))->render()
        ]);
    }

    public function read(Notification $notification)
    {
        $user = auth()->user();
        abort_if($user->id != $notification->user_id, 403);

        $notification->update([
            'is_read' => true
        ]);

        return $this->jsonSuccess('Notifcation marked as read!');
    }
}
