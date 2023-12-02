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
        <x-profile.nav active='subscription'/>
        <div class="content">
            <h1>My subscription</h1>
            @if ($sub && $sub->status == 'incomplete')
                <div class="pack danger">
                    <div class="pack-side">
                        <div class="pack-name">We did not received the payment for your new subscription.</div>
                        <span>
                            <b>One</b> day of payment processing is allowed, othervice subscription will be canceled.<br>
                            The invoice with payment link can be downloaded by clicking on Invoice Number in the table below.<br>
                            Please, contact us if you have any questions.
                        </span>
                        <br>
                    </div>
                    <div class="pack-button">
                        <a href="{{route('feedbacks.create')}}" class="button button-light">Contact us</a>
                    </div>
                </div>
            @endif
            <div class="pack {{$sub ? ($sub->status == 'canceled' ? 'warning' : '') : 'default'}}">
                <div class="pack-side">
                    @if ($sub)
                        <div class="pack-name">Active subscription «<span class="orange">{{$sub->plan->title}}</span>»</div>
                        <div class="pack-text">
                            @if ($sub->status == 'canceled')
                                <span>Subscription is canceled. It will not be prolonged.</span>
                            @else
                                <x-sub-cancel-form btnclass="cancel-sub-btn" btntext="Cancel subscription" />
                            @endif
                        </div>
                    @else
                        <div class="pack-name">Subscription is not active</div>
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
                <div class="history-table" style="margin-bottom:20px">
                    <table>
                        <tbody>
                            <tr>
                                <th>
                                    Invoice
                                    <span class="help-tooltip-icon" title="Click the invoice number to download invoice or to get payment receipt">
                                        @svg('icons/info.svg')
                                    </span>
                                </th>
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
                                    <td>{{$cycle->plan->title}}</td>
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
                <div class="pagination-field">
                    {{ $cycles->appends(request()->input())->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

@endsection
