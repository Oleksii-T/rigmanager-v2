@extends('layouts.app')

@section('content')
    <div class="wrapper_main pt-74">
        <main class="content">
            <section class="sing-in">
                <div class="offer-image">
                    <img src="{{asset('img/graf.svg')}}">
                </div>
                <div class="container sing-in__container">
                    <form action="{{ route('password.email') }}" method="post" class="sing-in__form">
                        @csrf
                        <h3 class="sing-in__title">
                            Forgot password
                        </h3>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="input-group">
                            <label class="input-group__title">Email address</label>
                            <input type="text" class="input" name="email">
                            @error('email')
                                <span class="input-error">{{$message}}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-sm btn-blue">
                            Send password reset link
                        </button>
                        <p class="login-form__text">Already have an account? <a href="{{route('login')}}" class="blue-link">Login</a></p>
                    </form>
                </div>
            </section>
        </main>
        <footer class="footer">
        </footer>
    </div>
@endsection
