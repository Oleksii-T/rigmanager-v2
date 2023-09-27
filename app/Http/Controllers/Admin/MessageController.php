<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $users = User::latest()->select('id', 'name')->get();

        if (!$request->ajax()) {
            return view('admin.messages.index', compact('users'));
        }

        $chats = [];
        $chatCodes = [];

        if ($request->user_id)  {
            $chatCodesRaw = Message::query()
                ->where('user_id', $request->user_id)
                ->orWhere('reciever_id', $request->user_id)
                ->select('user_id', 'reciever_id')
                ->get();
        } else {
            $chatCodesRaw = Message::query()
                ->distinct()
                ->select('user_id', 'reciever_id')
                ->get();
        }

        foreach ($chatCodesRaw as $chatRaw) {
            $ids = [$chatRaw->user_id, $chatRaw->reciever_id];
            $code = implode('-', $ids);
            $code2 = implode('-', array_reverse($ids));

            if (in_array($code, $chatCodes) || in_array($code2, $chatCodes)) {
                continue;
            }

            $chatCodes[] = $code;
            $chatMessages = Message::getChatMessages($ids)->get();
            $chats[] = [
                'uids' => $ids,
                'users' => User::whereIn('id', $ids)->get(),
                'count' => $chatMessages->count(),
                'unread' => $chatMessages->where('is_read', false)->count(),
                'last_message' => $chatMessages->first()->message,
                'last_at' => $chatMessages->first()->created_at,
            ];
        }

        return $this->jsonSuccess('', [
            'html' =>  view('admin.messages.index-content', compact('users', 'chats'))->render()
        ]);
    }

    public function show(Request $request, $u1, $u2)
    {
        $messages = Message::getChatMessages([$u1, $u2]);

        if (!$request->ajax()) {
            $u1 = User::findOrFail($u1);
            $u2 = User::findOrFail($u2);
            $total = $messages->count();
            $lastAt = $messages->first()->created_at;
            $messages = $messages->get();

            return view('admin.messages.show', compact('u1', 'u2', 'lastAt', 'total'));
        }

        return Message::dataTable($messages);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'reciever_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string'],
        ]);

        Message::create($data);

        return $this->jsonSuccess('Message send successfully', [
            'redirect' => route('admin.messages.index')
        ]);
    }

    public function destroy(Request $request, Message $message)
    {
        $message->delete();

        return $this->jsonSuccess('Message deleted successfully');
    }
}
