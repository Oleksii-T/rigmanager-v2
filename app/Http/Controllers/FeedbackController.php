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
        $input = $request->validated();
        $input['user_id'] = auth()->id();

        Feedback::create($input);

        return $this->jsonSuccess(trans('messages.feedback.created'));
    }
}
