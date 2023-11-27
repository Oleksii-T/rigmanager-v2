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
                    <div class="history-form">
                        <div class="history-form-line">
                            <div class="history-form-input" style="padding-right: 10px">
                                <select name="is_read" class="input notif-table-filter">
                                    <option value="">Status</option>
                                    <option value="1">Read</option>
                                    <option value="0">Unread</option>
                                </select>
                            </div>
                            <div class="history-form-input" style="padding-right: 10px">
                                <select name="type" class="input notif-table-filter">
                                    <option value="">Level</option>
                                    @foreach ($levels as $level => $name)
                                        <option value="{{$level}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="history-form-input notif-table-filter">
                                <input type="text" name="date" class="input input-date daterangepicker-mult" style="width: 240px">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="notifications-wrpr">
                    <x-profile.notifications-table :notifications="$notifications" />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/notifications.js')}}"></script>
@endsection
