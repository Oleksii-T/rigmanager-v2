@extends('layouts.page')

@section('meta')
	<title>{{$user->name}}</title>
	<meta name="description" content="{{$user->name}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.catalog')" :href="route('search')" i="2" />
    <x-bci :text="$user->name" i="3" islast="1"/>
@endsection

@section('style')
    <style>
        .main-u-section {
            background-color: rgb(32,32,32);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 40px;
        }
        .top-image {
            width: 100%;
            height:320px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .top-image img{
            width: 100%;
            object-fit: cover;
        }
        .main-info-cotainer {
            padding: 0px 20px;
            display: flex;
            justify-content: space-between;
            height: 45px;
            margin-bottom: 40px;
        }
        .avatar-name-container {
            display: flex;
        }
        .profile-ava{
            margin-top: -119px;
        }
        .name-container {
            padding-left: 20px;
        }
        .main-categs-cotainer {
            padding: 0px 20px;
            display: flex;
            justify-content: space-between;
        }

        .bio-container {
            padding: 15px 25px;
        }
        .bio-header{
            color: white;
            font-size: 120%;
        }
        .bio-content {
            white-space: pre-line;
        }
        .bio-content::first-letter{
            color: white;
            font-size: 110%;
        }

        .user-side-info {

        }
        .user-side-info a{
            color: #ff8d11
        }
    </style>
@endsection

@section('content')
    {{-- top section with image --}}
    <section class="main-u-section">

        {{-- top image --}}
        <div class="top-image">
            <img src="/icons/main-about-bg.jpg" alt="">
        </div>

        {{-- avatar, name and buttons --}}
        <div class="main-info-cotainer">
            {{-- avatar and name --}}
            <div class="avatar-name-container">
                <div class="profile-ava" style="background-image:url({{$user->avatar->url ?? asset('icons/emptyAva.svg')}})">
                </div>
                <div class="name-container">
                    <h2>{{$user->name}}</h2>
                </div>
            </div>

            {{-- buttons --}}
            <div>
                <button class="header-button">Chat</button>
                <button class="header-button">Contacts</button>
                @if ($currentUser->id == $user->id)
                    <button class="header-button">Edit</button>
                @endif
            </div>
        </div>

        {{-- categories --}}
        <div class="main-categs-cotainer">
            <p>
                Top categories:
                <a href="#">Drill Pipes</a>,
                <a href="#">DHM</a>,
                <a href="#">Mud Pumps</a>
            </p>
            <p>
                Total publications:
                <span style="color: white">{{$totalPosts}}</span>
            </p>
        </div>
    </section>

    <section class="prod user-side-info">
        {{-- bio --}}
        <div class="prod-content bio-container">
            <p class="bio-header">{{$user->name}}</p>
            <p class="bio-content">{{$user->bio}}</p>
        </div>

        {{-- sidebar --}}
        <div class="prod-side">
            <div class="prod-info">
                <div class="prod-info-item">
                    <div class="prod-info-name">Website</div>
                    <div class="prod-info-text">
                        <a target="_blank" href="{{$user->website}}">{{$user->website}}</a>
                    </div>
                </div>
                <div class="prod-info-item">
                    <div class="prod-info-name">Location</div>
                    <div class="prod-info-text">
                        {{countries()[$user->country]}}
                    </div>
                </div>
                <div class="prod-info-item">
                    <div class="prod-info-name">Facebook</div>
                    <div class="prod-info-text">
                        <a target="_blank" href="{{$user->facebook}}">Facebook.com</a>
                    </div>
                </div>
                <div class="prod-info-item">
                    <div class="prod-info-name">LinkedIn</div>
                    <div class="prod-info-text">
                        <a target="_blank" href="{{$user->linkedin}}">LinkedIn.com</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="ad-section">
        <h2>Latest posts by {{$user->name}}</h2>
        <div class="ad-list">
            <x-home-items :posts="$posts" />
            <div class="ad-col ad-col-more">
                <a href="{{route('search')}}" class="ad-more">@lang('ui.morePosts')</a>
            </div>
        </div>
    </div>
@endsection
