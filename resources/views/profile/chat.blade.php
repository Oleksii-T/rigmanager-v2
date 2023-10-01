@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.info')</title>
	<meta name="description" content="@lang('meta.description.user.info')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.profileInfo')" islast="1" i="2" />
@endsection

@section('style')
    <link media="all" rel="stylesheet" type="text/css" href="{{asset('css/chat.css')}}" />
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='chat'/>
        <div class="content">
            <h1>@lang('ui.chat')</h1>
            <livewire:chat />
        </div>
    </div>
@endsection
