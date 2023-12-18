<?php

namespace App\Http\Controllers;

use App\Enums\FeedbackStatus;
use App\Models\Feedback;
use App\Models\FeedbackBan;
use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedbacks.create');
    }

    public function store(Request $request, $type=null)
    {
        if ($type == 'report-category-fields') {
            $input = [
                'subject' => 'Post Category Suggestion Report',
                'name' => '',
                'email' => '',
                'text' => json_encode($request->data)
            ];
        } else {
            $input = $request->validate([
                'subject' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'text' => ['required', 'string', 'max:2000']
            ]);
        }

        $user = auth()->user();
        $ban = $user ? FeedbackBan::where('type', 'user')->where('value', $user->id)->first() : null;
        $ban ??= FeedbackBan::where('type', 'ip')->where('value', $request->ip())->first();
        $ban ??= FeedbackBan::where('type', 'name')->where('value', $input['name'])->first();
        $ban ??= FeedbackBan::where('type', 'email')->where('value', $input['email'])->first();

        if ($ban && $ban->is_active) {
            activity('feedback-bans')
                ->event('catch')
                ->withProperties(infoForActivityLog())
                ->on($ban)
                ->log('');

            if ($ban->action == 'abort') {
                abort(429);
            } else if ($ban->action == 'spam') {
                $input['status'] = FeedbackStatus::SPAM;
            }
        }

        $input['user_id'] = $user->id??null;

        Feedback::create($input);

        return $this->jsonSuccess(trans('messages.feedback.created'));
    }
}
