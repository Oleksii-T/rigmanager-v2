<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.feedbacks.index');
        }

        $feedbacks = Feedback::query();
        $read = $request->is_read ? $request->is_read : 'not-read';

        if ($read == 'read') {
            $feedbacks->where('is_read', true);
        } else if ($read == 'not-read') {
            $feedbacks->where('is_read', false);
        }

        return Feedback::dataTable($feedbacks);
    }

    public function show(Request $request, Feedback $feedback)
    {
        return view('admin.feedbacks.show', compact('feedback'));
    }

    public function read(Request $request, Feedback $feedback)
    {
        $feedback->update([
            'is_read' => true
        ]);

        return redirect()->back();
    }

    public function destroy(Request $request, Feedback $feedback)
    {
        $feedback->delete();

        return $this->jsonSuccess('Feedback updated successfully');
    }
}
