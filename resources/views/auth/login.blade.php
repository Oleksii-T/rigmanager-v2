@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.auth.login')}}</title>
	<meta name="description" content="{{__('meta.description.auth.login')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">{{__('ui.auth')}}</span>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    <div class="login">
        <div class="login-title">{{__('ui.auth')}}</div>
        <form method="POST" action="{{loc_url(route('login'))}}">
            @csrf
            <fieldset>
                <input class="input" type="email" name="email" value="{{old('email')}}" required autocomplete="email" autofocus placeholder="{{__('ui.login')}}">
                <input class="input" type="password" name="password" required autocomplete="current-password" placeholder="{{__('ui.password')}}">
                @error('email')
                    <script type="text/javascript">
                        document.getElementsByName("email")[0].className += " form-error";
                        document.getElementsByName("password")[0].className += " form-error";
                    </script>
                    <div class="form-error">{{$message}}</div>
                @enderror
                <div class="login-line">
                    <div class="check-item">
                        <input type="checkbox" class="check-input" id="ch1">
                        <label for="ch1" class="check-label">{{__('ui.remember me')}}</label>
                    </div>
                    <a href="{{loc_url(route('password.request'))}}" class="login-link">{{__('ui.forget password')}}</a>
                </div>
                <button class="button">{{__('ui.signIn')}}</button>
                <div class="social-buttons">
                    <a href="{{route('login.social', ['social'=>'facebook'])}}" class="social-fb"><img src="{{asset('icons/fb.svg')}}" alt=""></a>
                    <a href="{{route('login.social', ['social'=>'google'])}}" class="social-google"><img src="{{asset('icons/google.svg')}}" alt=""></a>
                </div>
                <div class="login-bottom">
                    {{__('ui.notSignUp?')}}<br>
                    <a href="{{loc_url(route('register'))}}">{{__('ui.signUp')}}</a>
                </div>
            </fieldset>
        </form>
    </div>
@endsection
