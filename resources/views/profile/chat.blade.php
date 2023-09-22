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
        .profile {
            min-height: 400px;
        }
        .chat-list {
            padding: 10px;
        }
        .chat-el {
            display: flex;
            margin-bottom: 10px;
            padding: 5px 10px;
            border-radius: 8px;
            border: 1px;
            cursor: pointer;
        }
        .chat-el.active {
            border: 1px solid #ff8d12;
            cursor: default;
        }
        .chat-el:hover  {
            background-color: rgb(40,40,40);
        }
        .chat-avatar {
            display: inline-block;
            width: 67px;
            height: 50px;
            border-radius: 50%;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-color: rgb(40,40,40);
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
            text-wrap: nowrap;
            max-width: 125px;
            overflow: hidden;
        }
        .chat-new-messages {
            background-color: #ff8d12;
            padding: 0px 7px;
            font-size: 85%;
            line-height: 20px;
            border-radius: 50%;
            color: white;
            max-height: 20px;
            margin-top: 4px;
        }
        .chat-rreaded-messages {
            width: 20px;
            padding-top: 4px;
        }
        .chat-date {
            font-size: 70%;
        }
        .chat-wraper {

        }
        .profile-content {
            padding: 15px 0px 15px 25px;
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
        .chat-loading-messages div{
            display: flex;
            justify-content: center;
            justify-items: center;
            width: 100%;
            height: 100%;
        }
        .chat-loading-messages img{
            width: 100px;
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
            display: flex;
            justify-items: center;
            padding-bottom: 5px;
        }
        .chat-m-user-avatar {
            display: inline-block;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-color: rgb(40,40,40);
        }
        .chat-m-user-name {
            padding-left: 5px;
            padding-right: 5px;
        }
        .chat-m-me .chat-m-wrpr{
            margin-left: auto;
            padding-right: 25px;
        }
        .chat-m-me .chat-m-user {
            text-align: right;
            font-size: 85%;
            justify-content: end;
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
        .chat-send-form{
            padding-right: 25px;
        }
        .chat-send-form > div{
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding: 5px 5px 5px 10px;
            border: 1px solid #505050;
            border-radius: 8px;
            margin-right: 25px;
        }
        .chat-send-form textarea{
            color: white;
            width: 100%;
            padding-right: 5px;
            resize: none;
        }
        .loading-el .loading-el-l{
            display: none;
            width: 34px;
        }
        .loading-el .loading-el-l img{
            height: 24px;
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
            <h1>@lang('ui.chat')</h1>
            @livewire('chat')
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/app.js')
    <script>
        $( document ).tooltip({
            position: {
                my: 'center bottom-5',
                at: 'center top',
                collision: 'flipfit'
            }
        });
    </script>
@endsection
