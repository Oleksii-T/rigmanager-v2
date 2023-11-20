@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.verify')</title>
	<meta name="description" content="@lang('meta.description.user.verify')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.registerVerify')" i="2" islast="1" />
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active=''/>
        <div class="content">
            <h1>@lang('ui.verifyNoticeThank')</h1>
            <p>@lang('ui.verifyNoticeBody')</p>
            <form method="POST" action="{{route('verification.send')}}">
                @csrf
                <button type="submit" class="button">@lang('ui.verifyClickToResend')</button>
            </form>
            @if (session('resent'))
                <div id="resendBody">
                    <p>@lang('ui.verifyNoticeResend')</p>
                </div>
            @endif
        </div>
    </div>
@endsection
