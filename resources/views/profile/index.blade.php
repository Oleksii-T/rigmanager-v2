@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.info')</title>
	<meta name="description" content="@lang('meta.description.user.info')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">@lang('ui.profileInfo')</span>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='profile'/>
        <div class="content">
            <h1>@lang('ui.profileInfo')</h1>
            <div class="profile">
                <div class="profile-side">
                    <div class="profile-ava" style="background-image:url({{$currentUser->avatar->url ?? asset('icons/emptyAva.svg')}})"></div>
                    <br>
                    <a href="{{route('profile.edit')}}" class="profile-edit-link">@lang('ui.edit')<br>@lang('ui.profile')</a>
                </div>
                <div class="profile-content">
                    <div class="profile-name">{{$currentUser->name}}
                    </div>
                    <div class="profile-info">
                        <div class="profile-info-title">@lang('ui.phone')</div>
                        @if ($currentUser->phone)
                            <div class="profile-info-text">{{$currentUser->phone}}</div>
                        @else
                            <div class="profile-info-text">@lang('ui.notSpecified')</div>
                        @endif
                    </div>
                    <div class="profile-info">
                        <div class="profile-info-title">@lang('ui.login')</div>
                        <div class="profile-info-text">{{$currentUser->email}}</div>
                    </div>
                    <div class="profile-info">
                        <div class="profile-info-title">@lang('ui.subscription')</div>
                        @if ( auth()->user()->subscription && auth()->user()->subscription->is_active )
                            <div class="profile-info-text green">@lang('ui.active') «{{auth()->user()->subscription->role_readable}}» @lang('ui.until') {{auth()->user()->subscription->expire_at}}</div>
                        @else
                            <div class="profile-info-text">@lang('ui.active')</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
