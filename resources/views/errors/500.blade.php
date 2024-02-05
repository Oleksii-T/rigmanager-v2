@extends('layouts.page')

@section('meta')
	<title>Server error | rigmanagers.com</title>
    <meta name="description" content="Server error">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci text="Server error" i="2" islast="1" />
@endsection

@section('style')
    <style>
        .main-block {
            flex-direction: column;
            align-items: center;
        }
        .error-code {
            margin-bottom: 45px;
            font-size: 300%;
        }
        .error-subtitle {

        }
        .error-text {
            color: white;
        }
        .error-buttons {
            display: flex;
        }
    </style>
@endsection

@section('content')
	<div class="main-block">
        <h1 class="error-code">500</h1>
        <p class="error-subtitle">Something went wrong...</p>
        <p class="error-text">Would you plese help us figure out what happened?</p>
        <div class="error-buttons">
            <div class="header-cabinet">
                <a href="{{route('feedbacks.create')}}" class="header-button">Of course!</a>
            </div>
            <div class="header-cabinet">
                <a href="{{route('index')}}" class="header-catalog">Take me home</a>
            </div>
        </div>
        
	</div>
@endsection
