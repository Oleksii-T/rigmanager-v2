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
    <style>
        .chat-list {
            padding: 10px;
        }
        .chat-el {
            display: flex;
            margin-bottom: 10px;
            padding: 5px 10px;
            border-radius: 8px;
            border: 1px;
        }
        .chat-el.active {
            border: 1px solid #ff8d12;
        }
        .chat-el:hover  {
            background-color: rgb(40,40,40);
        }
        .chat-avatar {
            width: 70px;
        }
        .chat-main-info-wrpr {
            text-align: left;
            padding-left: 5px;
            width: 100%;
        }
        .chat-main-info {
            display: flex;
            justify-content: space-between;
        }
        .chat-user-name {

        }
        .chat-date {
            font-size: 70%;
        }
        .chat-wraper {

        }
        .chat-m {
            display: flex;
        }
        .chat-loading-messages {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: #00000075;
            left: 0px;
            top: 0px;
        }
        .chat-messages {
            max-height: 50vh;
            overflow-y: scroll;
        }
        .chat-m-empty {
            width: 40%;
        }
        .chat-m-wrpr {

        }
        .chat-m-user {
            margin-bottom: 0px;
        }
        .chat-m-me .chat-m-user {
            text-align: right;
            font-size: 85%;
        }
        .chat-m-text {
            background-color: rgb(40,40,40);
            border-radius: 5px;
            padding: 5px 10px;
            white-space: pre-line;
        }
        .chat-m-date {
            font-size: 70%;
        }
        .chat-m-me .chat-m-date {
            text-align: right
        }
        .chat-send-form {
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding: 5px 5px 5px 10px;
            border: 1px solid #505050;
            border-radius: 8px;
        }
        .chat-send-form textarea{
            color: white;
            width: 100%;
            padding-right: 5px;
        }
        .loading-el .loading-el-l{
            display: none;
        }
        .loading-el:disabled .loading-el-l{
            display: inline-block;
        }
        .loading-el:disabled .loading-el-og{
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='chat'/>
        <div class="content">
            <h1>@lang('ui.profileInfo')</h1>
            @livewire('chat')
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/app.js')
    {{-- @livewireScripts --}}
    <script>
        // setTimeout(() => {
        //     console.log(`subscribe for echo events...`); //! LOG
        //     window.Echo.channel('chat.9').listen('MessageCreated', e => {
        //         console.log(` EVENT recieved:`, e)
        //     })
        // }, 1000);
    </script>
@endsection
