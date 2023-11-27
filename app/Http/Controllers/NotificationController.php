<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $levels = \App\Enums\NotificationType::all();
        $from = $request->from ?? now()->subMonth()->format('Y-m-d');
        $notifications = $user->notifications()
            ->when($request->is_read !== null, fn($q) => $q->where('is_read', $request->is_read))
            ->when($request->type !== null, fn($q) => $q->where('type', $request->type))
            ->when($request->to !== null, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->whereDate('created_at', '>=', $from)
            ->latest()
            ->paginate(10);

        if (!$request->ajax()) {
            return view('profile.notifications', compact('levels', 'notifications'));
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
