<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;

class Chat extends Component
{
    #[Locked]
    public $chats;

    #[Locked]
    public $messages;

    #[Rule('required')]
    public $message;

    public $chatWith;

    #[Locked]
    public $messageError;

    public function render()
    {
        $this->log("Render. chatting with: $this->chatWith");

        if ($this->chatWith) {
            $this->markAsReaded();
            $this->composeChatMessages();
        }
        $this->chats = $this->getChats();

        return view('livewire.chat');
    }

    public function mount()
    {
        $this->log('MOUNT');

        $this->chatWith = request()->chat_with;
        $this->chats = $this->getChats();
        $this->messages = null;
    }

    public function getListeners()
    {
        $id = auth()->id();

        return [
            "echo:chat.$id,MessageCreated" => 'newMessageCreated',
        ];
    }

    public function sendMessage()
    {
        $this->messageError = '';
        if (!$this->message) {
            $this->messageError = 'Message is required';
            return;
        }

        if (!auth()->user()->isSub()) {
            $this->messageError = 'A subscriptoion required for this action. Learn more at <a href="/plans" style="color:#ff8d11">Paid plans</a> page.';
            return;
        }

        Message::create([
            'user_id' => auth()->id(),
            'reciever_id' => $this->chatWith,
            'message' => $this->message
        ]);
        $this->message = '';
    }

    //! deprecated
    public function see(Message $message)
    {
        // dlog("Chat@see: $message->id"); //! LOG

        if ($message->reciever_id == auth()->id() && !$message->is_read) {
            $message->update([
                'is_read' => true
            ]);
        }
        $this->skipRender();
    }

    public function newMessageCreated()
    {
        // do nothing, because new message
        // will be auto-discovered by render() method
    }

    private function getChats()
    {
        $user = auth()->user();
        $messages = $user->messages()->latest()->get();
        $chats = [];

        foreach ($messages as $message) {
            $chatWith = $user->id == $message->user_id
                ? $message->reciever
                : $message->user;
            $incomming = $message->reciever_id == $user->id;
            if (array_key_exists($chatWith->id, $chats)) {
                // $chats[$chatWith->id]['messages'][] = $message;
                if ($incomming && !$message->is_read) {
                    $chats[$chatWith->id]['unread']++;
                }
            } else {
                $unread = $messages->where('reciever_id', $chatWith->id)->where('is_read', false)->count();
                $this->log(" unred by $chatWith->id: $unread");
                $chats[$chatWith->id] = [
                    'user' => [
                        'name' => $chatWith->name,
                        'avatar' => userAvatar($chatWith),
                        'id' => $chatWith->id,
                    ],
                    'last_at' => $message->created_at->diffForHumans(),
                    'reciever_seen' => !$unread,
                    'unread' => $incomming ? ($message->is_read ? 0 : 1) : 0
                ];
            }
        }

        return $chats;
    }

    private function composeChatMessages()
    {
        $this->log("composeChatMessages. Selected: $this->chatWith");
        $users = [auth()->id(), $this->chatWith];

        $this->messages = Message::query()
            // ->select('user_id', 'reciever_id', 'created_at', 'message')
            ->whereIn('user_id', $users)
            ->whereIn('reciever_id', $users)
            ->oldest()
            ->get();
    }

    private function markAsReaded()
    {
        if (!$this->chatWith) {
            return;
        }

        $messages = Message::query()
            ->where('reciever_id', auth()->id())
            ->where('user_id', $this->chatWith)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    private function log($text)
    {
        return;
        $id = auth()->id();
        dlog("Chat log user #$id: $text"); //! LOG
    }
}

