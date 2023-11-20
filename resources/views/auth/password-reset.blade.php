@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.password')}}</title>
	<meta name="description" content="@lang('meta.description.user.password')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.passReset')" i="2" islast="1" />
@endsection

@section('content')
    <div class="login">
        <div class="login-title">@lang('ui.passReset')</div>
        <form id="form-pass-reset" method="POST" action="{{route('password.update')}}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <fieldset>
                <label class="label">@lang('ui.login') <span class="orange">*</span></label>
                <input class="input" type="email" name="email" value="{{old('email', request()->email)}}" placeholder="@lang('ui.login')">
                @error('email')
                    <div class="form-error">{{$message}}</div>
                @enderror

                <label class="label">@lang('ui.password') <span class="orange">*</span></label>
                <input class="input" id="password" type="password" name="password" placeholder="@lang('ui.password')">
                @error('password')
                    <div class="form-error">{{$message}}</div>
                @enderror
                <div class="form-note">@lang('ui.passwordHelp')</div>

                <label class="label">@lang('ui.rePass') <span class="orange">*</span></label>
                <input class="input" type="password" name="password_confirmation" placeholder="@lang('ui.rePass')">
                <div class="form-note">@lang('ui.rePassHelp')</div>

                <button class="button">@lang('Reset Password')</button>
                <div class="login-bottom">
                    <a href="{{route('login')}}">@lang('ui.backToSignIn')</a>
                </div>
            </fieldset>
        </form>
    </div>
@endsection
