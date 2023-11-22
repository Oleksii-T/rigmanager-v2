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
                        <div>
                            <label for="email">Card</label>
                            <div id="cardNumber"></div>
                        </div>
                        <div>
                            <label for="password">Expiration</label>
                            <div id="cardExp"></div>
                        </div>
                        <div>
                            <label for="password">CVC</label>
                            <div id="cardCVC"></div>
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
