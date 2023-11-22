@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.user.subscription')}}</title>
	<meta name="description" content="{{__('meta.description.user.subscription')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.mySubscription')" i="2" islast="1"/>
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='fav'/>
        <div class="content">
            <h1>My subscription</h1>
            <div class="pack">
                <div class="pack-side">
                    <div class="pack-name">Activated subscription «<span class="orange">Pro</span>»</div>
                    <div class="pack-text"><a class="not-ready" href="#">Cancel subscription</a></div>
                </div>
                <div class="pack-button">
                    <a href="{{route('plans.index')}}" class="button button-light">Change plan</a>
                </div>
            </div>

            <div class="history">
                <div class="history-top">
                    <div class="history-title">History</div>
                </div>
                <div class="history-table">
                    <table>
                        <tbody><tr>
                            <th>№ Operation</th>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                        <tr><td>00000 <span class="history-table-date">2023-06-01</span></td>
                        <td>Pro</td>
                        <td>2023-06-01</td>
                        <td></td>
                        <td>0</td>
                        <td><span class="history-status history-status-active">Active</span></td>

                    </tr></tbody></table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

@endsection
