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

    public $selected;

    public function render()
    {
        $this->log("Render. selected: $this->selected");

        if ($this->selected) {
            $this->markAsReaded();
            $this->composeChatMessages();
        }

        return view('livewire.chat');
    }

    public function mount()
    {
        $user = auth()->user();

        dlog("Chat@mount for $user->id"); //! LOG

        $messages = $user->messages()->get();
        $chats = [];

        foreach ($messages as $message) {
            $chatWith = $user->id == $message->user_id
                ? $message->reciever
                : $message->user;
            $forUser = $message->reciever_id == $user->id;
            if (array_key_exists($chatWith->id, $chats)) {
                // $chats[$chatWith->id]['messages'][] = $message;
                if ($forUser && !$message->is_seen) {
                    $chats[$chatWith->id]['unread']++;
                }
            } else {
                $chats[$chatWith->id] = [
                    'user' => [
                        'name' => $chatWith->name
                    ],
                    // 'messages' => [$message],
                    'unread' => $forUser ? ($message->is_seen ? 0 : 1) : 0
                ];
            }
        }

        $this->chats = $chats;
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
        if (!$this->message) {
            return;
        }
        Message::create([
            'user_id' => auth()->id(),
            'reciever_id' => $this->selected,
            'message' => $this->message
        ]);
        $this->message = '';
    }

    //! deprecated
    public function see(Message $message)
    {
        // dlog("Chat@see: $message->id"); //! LOG

        if ($message->reciever_id == auth()->id() && !$message->is_seen) {
            $message->update([
                'is_seen' => true
            ]);
        }
        $this->skipRender();
    }

    public function newMessageCreated()
    {
        // do nothing, because new message
        // will be auto-discovered by render() method
    }

    private function composeChatMessages()
    {
        $this->log("composeChatMessages. Selected: $this->selected");
        $users = [auth()->id(), $this->selected];

        $this->messages = Message::query()
            // ->select('user_id', 'reciever_id', 'created_at', 'message')
            ->whereIn('user_id', $users)
            ->whereIn('reciever_id', $users)
            ->oldest()
            ->get();
    }

    private function markAsReaded()
    {
        Message::query()
            ->where('reciever_id', auth()->id())
            ->where('is_seen', false)
            ->update(['is_seen' => true]);
    }

    private function log($text)
    {
        $id = auth()->id();
        dlog("Chat log user #$id: $text"); //! LOG
    }
}

