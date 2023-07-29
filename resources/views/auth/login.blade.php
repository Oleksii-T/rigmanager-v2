@extends('layouts.page')

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">@lang('ui.auth')</span>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    <div class="login">
        <div class="login-title">@lang('ui.auth')</div>
        <form method="POST" action="{{route('login')}}" class="general-ajax-submit">
            @csrf
            <fieldset>
                <input class="input" type="email" name="email" value="{{old('email')}}" placeholder="@lang('ui.login')">
                <div data-input="email" class="form-error"></div>
                <input class="input" type="password" name="password" placeholder="@lang('ui.password')">
                <div data-input="password" class="form-error"></div>
                <div class="login-line">
                    <div class="check-item">
                        <input type="checkbox" class="check-input" id="ch1">
                        <label for="ch1" class="check-label">@lang('ui.remember me')</label>
                    </div>
                    <a href="{{route('password.request')}}" class="login-link">@lang('ui.forget password')</a>
                </div>
                <button type="submit" class="button">@lang('ui.signIn')</button>
                <div class="social-buttons">
                    @if (\App\Models\Setting::get('facebook_auth_enabled'))
                        <a href="{{route('auth.social', 'facebook')}}" class="social-fb">
                            <img src="{{asset('icons/fb.svg')}}" alt="">
                        </a>
                    @endif
                    @if (\App\Models\Setting::get('google_auth_enabled'))
                        <a href="{{route('auth.social', 'google')}}" class="social-google">
                            <img src="{{asset('icons/google.svg')}}" alt="">
                        </a>
                    @endif
                    @if (\App\Models\Setting::get('twitter_auth_enabled'))
                        <a href="{{route('auth.social', 'twitter')}}" class="social-twitter">
                            <img src="{{asset('icons/twitter.svg')}}" alt="">
                        </a>
                    @endif
                    @if (\App\Models\Setting::get('linkedin_auth_enabled'))
                        <a href="{{route('auth.social', 'linkedin')}}" class="social-linkedin">
                            <img src="{{asset('icons/linkedin.svg')}}" alt="">
                        </a>
                    @endif
                    @if (\App\Models\Setting::get('apple_auth_enabled'))
                        <a href="{{route('auth.social', 'apple')}}" class="social-apple">
                            <img src="{{asset('icons/apple.svg')}}" alt="">
                        </a>
                    @endif
                </div>
                <div class="login-bottom">
                    @lang('ui.notSignUp?')<br>
                    <a href="{{route('register')}}">@lang('ui.signUp')</a>
                </div>
            </fieldset>
        </form>
    </div>
@endsection
