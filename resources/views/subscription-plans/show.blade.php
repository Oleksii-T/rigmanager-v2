@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.subscription')}}</title>
    <meta name="description" content="{{__('meta.description.info.subscription')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.footerSubscription')" :href="route('plans.index')" i="2" />
    <x-bci :text="$subscriptionPlan->title . ' plan'" i="3" islast="1" />
@endsection

@section('style')
    <style>
        .payment-field {
            display: block;
            /* width: 100%; */
            height: 44px;
            padding: 0 15px;
            margin: 0 0 27px;
            font-size: 16px;
            color: #999999;
            background: none;
            border: 1px solid #505050;
            border-radius: 8px;
            transition: all 0.3s linear;
        }
    </style>
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='plans'/>

		<div class="content">
			<h1>Subscribe to {{$subscriptionPlan->title}} plan</h1>
            <p>${{$subscriptionPlan->price}} / {{$subscriptionPlan->interval}}</p>
            <div>
                <form class="subscribe-form">
                    @csrf
                    <span class="d-none" id="user-data" data-email="{{$currentUser->email}}"  data-name="{{$currentUser->name}}" data-plan="{{$subscriptionPlan->id}}"></span>
                    <div class="porfile-change__form-group">
                        <div class="row">
                            <div class="col-12">
                                <label class="label">Card</label>
                                <div class="payment-field" id="cardNumber"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="label">Expiration</label>
                                <div class="payment-field" id="cardExp"></div>
                            </div>
                            <div class="col-6" style="padding-left: 10px">
                                <label class="label">CVC</label>
                                <div class="payment-field" id="cardCVC"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="label">Cardholder name</label>
                                <input class="input" name="cardhoder_name" type="text" required>
                            </div>

                            <div class="col-6" style="padding-left: 10px">
                                <label class="label">Address</label>
                                <input class="input" name="billing_address" type="text" required>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="button">Subscribe</button>
                    </div>
                </form>
            </div>
		</div>
	</div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="module" src="{{asset('js/payments.js')}}"></script>
@endsection
