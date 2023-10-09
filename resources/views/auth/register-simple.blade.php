@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.auth.reg-simple')</title>
	<meta name="description" content="@lang('meta.description.auth.reg-simple')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.registerSimple.signUpSimple')" i="2" islast="1" />
@endsection

@section('content')
    <div class="reg">
        <div class="reg-content">
            <h1>@lang('ui.registerSimple.signUpSimple')</h1>
            <p style="white-space: pre-line">@lang('ui.registerSimple.help')</p>
            <form action="{{$submitUrl}}" method="POST" class="general-ajax-submit">
                @csrf
                <fieldset>
                    <div class="reg-wrap">
                        <div class="reg-col">
                            <label class="label">@lang('ui.login')</label>
                            <input class="input" type="text" name="email" value="{{$user->email}}" readonly>
                            <div class="form-note">@lang('ui.registerSimple.loginHelp')</div>
                        </div>
                        <div class="reg-col">
                            <label class="label">@lang('ui.userName')</label>
                            <input class="input" type="text" value="{{$user->name}}" readonly>
                            <div class="form-note">@lang('ui.registerSimple.userNameHelp')</div>
                        </div>
                    </div>
                    <div class="reg-wrap">
                        <div class="reg-col">
                            <label class="label">@lang('ui.password') <span class="orange">*</span></label>
                            <input class="input" id="password" type="password" name="password" placeholder="@lang('ui.password')">
                            <div data-input="password" class="form-error"></div>
                            <div class="form-note">@lang('ui.passwordHelp')</div>
                        </div>
                        <div class="reg-col">
                            <label class="label">@lang('ui.rePass') <span class="orange">*</span></label>
                            <input class="input" type="password" name="password_confirmation" placeholder="@lang('ui.rePass')">
                            <div class="form-note">@lang('ui.rePassHelp')</div>
                        </div>
                        <div class="reg-col">
                            <div class="check-block">
                                <div class="check-item">
                                    <input type="checkbox" class="check-input" id="ch1" name="agreement">
                                    <label for="ch1" class="check-label">@lang('ui.iAgree') «<a href="{{route('terms')}}">@lang('ui.iAgreeLink')</a>»</label>
                                </div>
                            </div>
                            <div data-input="agreement" class="form-error"></div>
                            <button type="submit" class="button">@lang('ui.makeSignUp')</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
