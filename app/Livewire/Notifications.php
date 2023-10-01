<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;

class Notifications extends Component
{
    #[Locked]
    public $page = 1;

    #[Locked]
    public $notifications = [];

    #[Locked]
    public $hasMore = false;

    #[Locked]
    public $unreadCount = 0;

    #[Locked]
    public $allNotifsIds = [];

    private $perPage = 3;

    public function mount()
    {
        $this->log('MOUNT');

        $user = auth()->user();

        if (!$user) {
            return;
        }

        $this->allNotifsIds = $user->notifications()->read(false)->pluck('id')->toArray();
        $this->unreadCount = count($this->allNotifsIds);
        $this->hasMore = $this->unreadCount > $this->perPage;
    }

    public function render()
    {
        $this->log('REANDER. allNotifsIds:' . json_encode($this->allNotifsIds));
        $user = auth()->user();

        if (!$user) {
            return view('livewire.notifications');
        }

        $visible = $this->perPage * $this->page;
        $notifs = $user->notifications()
            ->whereIn('id', $this->allNotifsIds)
            ->limit($visible)
            ->latest()
            ->get();
        $this->hasMore = count($this->allNotifsIds) > $visible;
        $notifs = $this->formatNotifs($notifs);
        $this->notifications = $notifs;

        return view('livewire.notifications');
    }

    public function read($id)
    {
        $this->log('read');
        $user = auth()->user();
        $notif = $user->notifications()->where('id', $id)->firstOrFail();
        $notif->update([
            'is_read' => true
        ]);
        $this->unreadCount--;
    }

    public function readAll()
    {
        $this->log('read all');
        $user = auth()->user();
        $notif = $user->notifications()->whereIn('id', $this->allNotifsIds)->update([
            'is_read' => true
        ]);
        $this->unreadCount = 0;
    }

    public function more()
    {
        $this->page++;
    }

    private function formatNotifs($all)
    {
        $notifs = [];

        foreach ($all as $n) {
            $notifs[] = [
                'id' => $n->id,
                'text' => $n->text,
                'type' => $n->type,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at->diffForHumans(),
            ];
        }

        return $notifs;
    }

    private function log($text)
    {
        $id = auth()->id();
        dlog("Chat log user #$id: $text"); //! LOG
    }
}
