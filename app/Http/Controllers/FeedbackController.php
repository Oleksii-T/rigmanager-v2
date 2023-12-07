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

    public function store(Request $request, $type=null)
    {
        $spammers = [
            '185.234.216.114',
            '176.111.174.153',
            '62.122.184.194',
            'Robertsmoth'
        ];
        abort_if(in_array($request->ip(), $spammers) || in_array($request->name, $spammers), 429);

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

        $input['user_id'] = auth()->id();

        Feedback::create($input);

        return $this->jsonSuccess(trans('messages.feedback.created'));
    }
}
