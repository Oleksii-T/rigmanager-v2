@extends('layouts.app')

@section('content')
    <div class="wrapper_main pt-74">
        <main class="content">
            <section class="sing-in">
                <div class="offer-image">
                    <img src="{{asset('img/graf.svg')}}">
                </div>
                <div class="container sing-in__container">
                    <form action="{{ route('password.update') }}" method="post" class="sing-in__form">
                        @csrf
                        <input type="hidden" name="token" value="{{ request()->route('token') }}">
                        <h3 class="sing-in__title">
                            Password reset
                        </h3>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="input-group">
                            <label class="input-group__title">Email</label>
                            <input type="text" class="input" name="email" value="{{request()->input('email')}}">
                            @error('email')
                                <span class="input-error">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="input-group">
                            <label class="input-group__title">Password</label>
                            <input type="password" class="input" name="password">
                            @error('password')
                                <span class="input-error">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="input-group">
                            <label class="input-group__title">Confirm Password</label>
                            <input type="password" class="input" name="password_confirmation">
                            @error('password_confirmation')
                                <span class="input-error">{{$message}}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-sm btn-blue">
                            Reset password
                        </button>
                    </form>
                </div>
            </section>
        </main>
        <footer class="footer">
        </footer>
    </div>
@endsection
