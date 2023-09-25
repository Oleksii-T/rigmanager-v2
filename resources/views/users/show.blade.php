@extends('layouts.page')

@section('meta')
	<title>{{$user->name}}</title>
	<meta name="description" content="{{$user->bio ?? 'User page'}} | {{$user->name}}">
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
            <img src="{{$user->banner->url ?? asset('icons/main-about-bg.jpg')}}" alt="{{$user->banner->alt ?? 'User banner'}}">
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
                <button class="header-button not-ready">Chat</button>
                <button data-url="{{route('users.contacts', $user)}}" class="header-button show-contacts">
                    @lang('ui.showContacts')
                </button>
                @if ($currentUser->id == $user->id)
                    <a href="{{route('profile.index')}}" class="header-button">@lang('ui.edit')</a>
                @endif
            </div>
        </div>

        {{-- categories --}}
        <div class="main-categs-cotainer">
            <p>
                @lang('ui.topCategories'):
                @forelse ($categories as $category)
                    <a href="{{$category->getUrl()}}?author={{$user->slug}}">{{$category->name}}</a>@if(!$loop->last),@endif
                @empty
                    -
                @endforelse
            </p>
            <p>
                @lang('ui.totalPublications'):
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
                @if ($user->website)
                    <div class="prod-info-item">
                        <div class="prod-info-name">@lang('ui.website')</div>
                        <div class="prod-info-text">
                            <a target="_blank" href="{{$user->website}}">{{$user->website}}</a>
                        </div>
                    </div>
                @endif

                <div class="prod-info-item">
                    <div class="prod-info-name">@lang('ui.location')</div>
                    <div class="prod-info-text">
                        {{countries()[$user->country]}}
                    </div>
                </div>

                @if ($user->facebook)
                    <div class="prod-info-item">
                        <div class="prod-info-name">Facebook</div>
                        <div class="prod-info-text">
                            <a target="_blank" href="{{$user->facebook}}">{{$user->facebook_name}}</a>
                        </div>
                    </div>
                @endif

                @if ($user->linkedin)
                    <div class="prod-info-item">
                        <div class="prod-info-name">LinkedIn</div>
                        <div class="prod-info-text">
                            <a target="_blank" href="{{$user->linkedin}}">{{$user->linkedin_name}}</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="ad-section">
        @if ($totalPosts)
            <h2>@lang('ui.latestPostsBy') {{$user->name}}</h2>
            <div class="ad-list">
                <x-home-items :posts="$posts" />
                <div class="ad-col ad-col-more">
                    <a href="{{route('search', ['author'=>$user->slug])}}" class="ad-more">@lang('ui.morePosts')</a>
                </div>
            </div>
        @else
            <h2>@lang('ui.noLatestPostsBy')</h2>
        @endif
    </div>
@endsection
