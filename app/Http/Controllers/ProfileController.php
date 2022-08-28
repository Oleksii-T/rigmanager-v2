<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfilePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(ProfileRequest $request)
    {
        $input = $request->validated();
        $user = auth()->user();
        $user->update($input);
        $user->addAttachment($input['avatar']??null, 'avatar');

        return $this->jsonSuccess(trans('messages.profile.updated'));

    }

    public function password(ProfilePasswordRequest $request)
    {
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return $this->jsonSuccess(trans('messages.profile.updated-password'));
    }
}
