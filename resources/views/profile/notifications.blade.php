@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.user.mailer')}}</title>
	<meta name="description" content="{{__('meta.description.user.mailer')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.notifications')" i="2"  islast="1" />
@endsection

@section('style')
    <style>
        .notif-table-icon svg{
            width: 25px;
            height: 25px;
        }
        .history-table {
            overflow-y: auto;
            max-height: none;
        }
    </style>
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='notifications'/>
        <div class="content">
            <h1>{{__('ui.notifications')}} (<span class="orange">{{$notifications->count()}}</span>)</h1>
            <div class="history">
                <div class="history-top">
                    <div class="history-title">
                        {{__('ui.history')}}
                    </div>
                </div>
                <div class="history-table">
                    <table class="notifications-table">
                        <x-profile.notifications-table :notifications="$notifications" />
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
