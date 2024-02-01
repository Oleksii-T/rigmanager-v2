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
            height: 44px;
            padding: 0 15px 0 15px;
            margin: 0 0 27px;
            font-size: 16px;
            color: #999999;
            background: none;
            border: 1px solid #505050;
            border-radius: 8px;
            transition: all 0.3s linear;
        }
        .billing-info-wrapper {
            background: #1a1a1a;
            margin-bottom: 27px;
            border-radius: 0 0 8px 8px;
            padding: 27px 50px 0px 50px;
        }
    </style>
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='plans'/>

		<div class="content">
            <div style="display:flex;justify-content:space-between">
                <h1>
                    Subscription to
                    <span class="orange">{{$subscriptionPlan->title}}</span>
                    plan<br>
                    <small style="color: #999999">${{$subscriptionPlan->price}}/{{$subscriptionPlan->interval}}</small>
                </h1>
                <a target="_blank" href="https://stripe.com/" style="width:150px;padding-top:4px">
                    @svg('icons/powered_by_stripe_blurplee.svg')
                </a>
            </div>
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
                    </div>

                    <div class="billing-info-wrapper">
                        <p>
                            Billing Information
                            <x-tooltop text="We ask for your cardholder name and billing address to ensure transaction security and prevent fraud. This helps verify your identity and keeps your payment safe." />
                        </p>
                        <div class="row">
                            <div class="col-12">
                                <label class="label">Cardholder name</label>
                                <input class="input" name="cardhoder_name" type="text" required>
                            </div>
                            <div class="col-6">
                                <label class="label">Country</label>
                                <select name="country" class="input" required>
                                    @foreach (countries() as $code => $name)
                                        <option value="{{$code}}" @selected($code == $currentUser->country)>{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6" style="padding-left: 10px">
                                <label class="label">City</label>
                                <input class="input" name="city" type="text" required>
                            </div>
                            <div class="col-6">
                                <label class="label">
                                    Address Line 1
                                    <x-tooltop text="Primary address line, such as street name and number"/>
                                </label>
                                <input class="input" name="line1" type="text" required>
                            </div>
                            <div class="col-6" style="padding-left: 10px">
                                <label class="label">
                                    Address Line 2
                                    <x-tooltop text="Additional address information, such as apartment or suite number, if applicable" />
                                </label>
                                <input class="input" name="line2" type="text">
                            </div>
                            <div class="col-6">
                                <label class="label">Postal Code</label>
                                <input class="input" name="postal_code" type="text" required>
                            </div>
                            <div class="col-6" style="padding-left: 10px">
                                <label class="label">
                                    State
                                    <x-tooltop text="or region/province" />
                                </label>
                                <input class="input" name="state" type="text">
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
