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

        $chats = Message::getChats($request->user_id);

        return $this->jsonSuccess('', [
            'html' =>  view('admin.messages.table', compact('chats'))->render()
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

    public function read(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'reciever_id' => ['required', 'exists:users,id'],
        ]);

        $messages = Message::query()
            ->where('user_id', $data['user_id'])
            ->where('reciever_id', $data['reciever_id'])
            ->get();

        foreach ($messages as $message) {
            $message->update(['is_read' => true]);
        }

        return $this->jsonSuccess('Message Read successfully', [
            'reload' => true
        ]);
    }

    public function destroy(Request $request, Message $message)
    {
        $message->delete();

        return $this->jsonSuccess('Message deleted successfully');
    }
}
