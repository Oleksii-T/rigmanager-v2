<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedbacks.create');
    }

    public function store(FeedbackRequest $request)
    {
        $spammers = [
            '185.234.216.114',
            '176.111.174.153',
            '62.122.184.194',
            'Robertsmoth'
        ];
        abort_if(in_array($request->ip(), $spammers) || in_array($request->name, $spammers), 429);

        $input = $request->validated();
        $input['user_id'] = auth()->id();
        $input['ip'] = $request->ip();
        $input['user_agent'] = $request->userAgent();;

        Feedback::create($input);

        return $this->jsonSuccess(trans('messages.feedback.created'));
    }
}
