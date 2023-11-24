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
                    @if ($sub)
                        <div class="pack-name">Activated subscription «<span class="orange">{{$sub->plan->title}}</span>»</div>
                        <div class="pack-text"><a class="not-ready" href="#">Cancel subscription</a></div>
                    @else
                        <div class="pack-name">{{__('ui.planActivated')}} «<span class="orange">{{__('ui.planStart')}}</span>»</div>
                        <div class="pack-text"><span class="pack-text-min">{{__('ui.planStartChoosedHelp')}}</span></div>
                    @endif
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
                        <tbody>
                            <tr>
                                <th>№ Operation</th>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Payment</th>
                                <th>Status</th>
                            </tr>
                            @forelse ($cycles as $cycle)
                                <tr>
                                    <td>{{$cycle->id}} <span class="history-table-date">?</span></td>
                                    <td>{{$cycle->subscription->plan->title}}</td>
                                    <td>{{$cycle->created_at->format('Y-m-d')}}</td>
                                    <td>{{$cycle->expire_at->format('Y-m-d')}}</td>
                                    <td>{{$cycle->price}}</td>
                                    <td>
                                        @if ($cycle->is_active)
                                            <span class="history-status history-status-active">Active</span>
                                        @else
                                            <span class="history-status">Expired</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        No subscriptions
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

@endsection
