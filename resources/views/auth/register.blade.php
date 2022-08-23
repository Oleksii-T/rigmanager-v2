@extends('layouts.app')

@section('content')
    <div class="wrapper_main pt-74">
        <main class="content">
            <section class="sing-in">
                <div class="offer-image">
                    <img src="{{asset('img/graf.svg')}}">
                </div>
                <div class="container sing-in__container">
                    <form action="{{route('register')}}" class="sing-in__form general-ajax-submit" method="POST">
                        @csrf
                        <h3 class="sing-in__title">
                            Create your free account
                        </h3>
                        <div class="login-from">
                            @if (\App\Models\Setting::get('google_client_id') && \App\Models\Setting::get('google_client_secret'))
                                <a href="{{route('auth.social', 'google')}}" class="login-from__item">
                                    <img src="{{asset('img/flat-color-icons_google.svg')}}" alt="">
                                    <span>Login with Google</span>
                                </a>
                            @endif
                            @if (\App\Models\Setting::get('facebook_client_id') && \App\Models\Setting::get('facebook_client_secret'))
                                <a href="{{route('auth.social', 'facebook')}}" class="login-from__item">
                                    <img src="{{asset('img/facebook.svg')}}" alt="">
                                    <span>Login with Facebook</span>
                                </a>
                            @endif
                            @if (\App\Models\Setting::get('twitter_client_id') && \App\Models\Setting::get('twitter_client_secret'))
                                <a href="{{route('auth.social', 'twitter')}}" class="login-from__item">
                                    <img src="{{asset('img/akar-icons_twitter-fill.svg')}}" alt="">
                                    <span>Login with Twitter</span>
                                </a>
                            @endif
                        </div>
                        <div class="divider">Or</div>
                        <div class="input-group">
                            <label class="input-group__title">Name</label>
                            <input type="text" class="input" name="name">
                            <span data-input="name" class="input-error"></span>
                        </div>
                        <div class="input-group">
                            <label class="input-group__title">Email address</label>
                            <input type="text" class="input" name="email" placeholder="alma.lawson@example.com">
                            <span data-input="email" class="input-error"></span>
                        </div>
                        <div class="input-group">
                            <label class="input-group__title">Password</label>
                            <div class="input-wrapper">
                                <input type="password" class="input" name="password" placeholder="•••••••••••••••••••••">
                                <button type="button" class="input-button"><img src="{{asset('img/eye-cross_1.svg')}}" alt=""></button>
                            </div>
                            <span data-input="password" class="input-error"></span>
                        </div>
                        <div class="input-group">
                            <label class="input-group__title">Confirm Password</label>
                            <div class="input-wrapper">
                                <input type="password" class="input" name="password_confirmation" placeholder="•••••••••••••••••••••">
                                <button type="button" class="input-button"><img src="{{asset('img/eye-cross_1.svg')}}" alt=""></button>
                            </div>
                        </div>
                        <div style="margin-bottom: 15px">
                            <div class="form-row">
                                <label class="module__check">
                                    <input type="checkbox" name="privacy_policy" checked>
                                    <span class="check"></span>
                                    <span class="text-module">I agree to the Terms & Conditions</span>
                                </label>
                            </div>
                            <span data-input="privacy_policy" class="input-error"></span>
                        </div>
                        <button type="submit" class="btn btn-sm btn-blue">
                            Sign Up
                        </button>
                        <p class="login-form__text">Already have an account? <a href="{{route('login')}}" class="blue-link">Login</a></p>
                    </form>
                </div>
            </section>
        </main>

        <x-footer />
    </div>
@endsection
