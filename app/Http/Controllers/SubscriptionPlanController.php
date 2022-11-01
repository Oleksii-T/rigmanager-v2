<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request)
    {
        return view('plans.index');
    }

    public function subscribe(Request $request)
    {
        return view('plans.subscribe');
    }
}
