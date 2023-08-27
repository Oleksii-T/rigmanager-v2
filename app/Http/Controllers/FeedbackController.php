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
        if ($request->ip() == '176.111.174.153') {
            abort(429);
        }

        $input = $request->validated();
        $input['user_id'] = auth()->id();
        $input['ip'] = $request->ip();
        $input['user_agent'] = $request->userAgent();;

        Feedback::create($input);

        return $this->jsonSuccess(trans('messages.feedback.created'));
    }
}
