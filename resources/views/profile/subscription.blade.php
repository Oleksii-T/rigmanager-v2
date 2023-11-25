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
                        <div class="pack-name">Active subscription «<span class="orange">{{$sub->plan->title}}</span>»</div>
                        <div class="pack-text">
                            @if ($sub->status == 'canceled')
                                <span>Subscription is canceled. It will not be prolonged.</span>
                            @else
                                <form action="{{route('subscriptions.cancel')}}" method="POST" class="general-ajax-submit show-full-loader ask" data-asktitle="Are you sure?" data-asktext="Please, consider contacting us if you have any complaints about paid experience.
                                Subscription will active to the end of current paid cycle." data-askyes="Yes, cancel" data-askno="Nevemind">
                                    @csrf
                                    <button type="submit" class="cancel-sub-btn">Cancel subscription</button>
                                </form>
                            @endif
                        </div>
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
                                <th>Invoice</th>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Payment</th>
                                <th>Status</th>
                            </tr>
                            @forelse ($cycles as $cycle)
                                <tr>
                                    <td>
                                        @if ($cycle->invoice)
                                            <form action="{{route('subscriptions.invoice-url', $cycle)}}" method="post" class="general-ajax-submit">
                                                @csrf
                                                <button type="submit">{{$cycle->invoice['number']}}</button>
                                            </form>
                                        @else
                                            {{str_pad($cycle->id, 4, '0', STR_PAD_LEFT)}}
                                        @endif
                                        {{-- <span class="history-table-date">{{str_pad($cycle->id, 4, '0', STR_PAD_LEFT)}}</span> --}}
                                    </td>
                                    <td>{{$cycle->subscription->plan->title}}</td>
                                    <td>{{$cycle->created_at->format('Y-m-d')}}</td>
                                    <td>{{$cycle->expire_at->format('Y-m-d')}}</td>
                                    <td>{{'$' . number_format($cycle->price)}}</td>
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
