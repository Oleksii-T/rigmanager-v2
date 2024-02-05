@extends('layouts.page')

@section('meta')
	<title>Not found | rigmanagers.com</title>
    <meta name="description" content="Not found">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci text="Not found" i="2" islast="1" />
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
        .top-form form {
            width: 650px;
        }
    </style>
@endsection

@section('content')
	<div class="main-block">
        <h1 class="error-code">404</h1>
        <p class="error-subtitle">The page you were looking for does not exist</p>
        <p class="error-text">Try searching Rigmanagers.com</p>
        <div class="top-form">
            <form action="{{route('search')}}">
                <fieldset>
                    <div class="top-form-line">
                        <input type="text" class="input typeahead-input" data-ttt="title" name="search" placeholder="@lang('ui.searchEquipment')" required>
                        <button class="button">@lang('ui.search')</button>
                    </div>
                </fieldset>
            </form>
        </div>
	</div>
@endsection
