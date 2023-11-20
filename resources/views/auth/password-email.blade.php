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
        <form action="{{route('password.email')}}" method="POST">
            @csrf
            <fieldset>
                <input class="input" type="email" name="email" value="{{old('email')}}" placeholder="@lang('ui.login')">
                @error('email')
                    <div class="form-error">{{$message}}</div>
                @enderror
                <div class="form-note">@lang('ui.passResetEmailHelp')</div>
                <button class="button">@lang('ui.sendPassResetLink')</button>
                <div class="login-bottom">
                    <a href="{{route('login')}}">@lang('ui.backToSignIn')</a>
                </div>
            </fieldset>
        </form>
    </div>
@endsection
