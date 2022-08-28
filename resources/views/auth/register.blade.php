@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.auth.reg')</title>
	<meta name="description" content="@lang('meta.description.auth.reg')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">@lang('ui.signUp')</span>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    <div class="reg">
        <div class="reg-content">
            <h1>@lang('ui.signUp')</h1>
            <form action="{{route('register')}}" method="POST" class="general-ajax-submit">
                @csrf
                <fieldset>
                    <div class="reg-wrap">
                        <div class="reg-col">
                            <label class="label">@lang('ui.login') <span class="orange">*</span></label>
                            <input class="input" type="text" name="email" placeholder="mail.mail@mail.com">
                            <div data-input="email" class="form-error"></div>
                            <div class="form-note">@lang('ui.loginHelp')</div>
                        </div>
                        <div class="reg-col">
                            <label class="label">@lang('ui.phone')</label>
                            <input class="input format-phone" type="text" name="phone_raw" placeholder="+38 ( _ _ _ ) _ _ _ - _ _ - _ _">
                            <div data-input="phone" class="form-error"></div>
                            <div class="form-note">@lang('ui.phoneHelp')</div>
                        </div>
                        <div class="reg-col">
                            <label class="label">@lang('ui.userName') <span class="orange">*</span></label>
                            <input class="input" type="text" name="name" placeholder="@lang('ui.userName')">
                            <div data-input="name" class="form-error"></div>
                            <div class="form-note">@lang('ui.userNameHelp')</div>
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
        <div class="reg-side">
            <div class="reg-social-title">@lang('ui.socialSignInTitle')</div>
            <div class="reg-social-text">@lang('ui.socialSignIn')</div>
            <div class="social-buttons">
                @if (\App\Models\Setting::get('facebook_auth_enabled'))
                    <a href="{{route('auth.social', 'facebook')}}" class="social-fb"><img src="{{asset('icons/fb.svg')}}" alt=""></a>
                @endif
                @if (\App\Models\Setting::get('google_auth_enabled'))
                    <a href="{{route('auth.social', 'google')}}" class="social-google"><img src="{{asset('icons/google.svg')}}" alt=""></a>
                @endif
                @if (\App\Models\Setting::get('twitter_auth_enabled'))
                    <a href="{{route('auth.social', 'twitter')}}" class="social-twitter"><img src="{{asset('icons/twitter.svg')}}" alt=""></a>
                @endif
                @if (\App\Models\Setting::get('linkedin_auth_enabled'))
                    <a href="{{route('auth.social', 'linkedin')}}" class="social-linkedin"><img src="{{asset('icons/linkedin.svg')}}" alt=""></a>
                @endif
                @if (\App\Models\Setting::get('apple_auth_enabled'))
                    <a href="{{route('auth.social', 'apple')}}" class="social-apple"><img src="{{asset('icons/apple.svg')}}" alt=""></a>
                @endif
            </div>
        </div>
    </div>
@endsection
