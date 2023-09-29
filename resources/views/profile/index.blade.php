@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.info')</title>
	<meta name="description" content="@lang('meta.description.user.info')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.profileInfo')" i="2" islast="1"/>
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset('css/profile-edit.css')}}" />
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='profile'/>
        <div class="content">
            <h1>@lang('ui.profileInfo')</h1>
            <form action="{{route('profile.update')}}" method="POST" class="profile general-ajax-submit">
                @csrf
                @method('PUT')

                <section class="u-p-section main-u-section">
                    {{-- top image --}}
                    <div class="top-image show-uploaded-file-preview" data-trigger="#banner-input">
                        <img src="{{$currentUser->banner->url ?? asset('icons/main-about-bg.jpg')}}" alt="{{$currentUser->banner->alt ?? 'User banner'}}" class="custom-file-preview">
                        <div class="img-edit-btn">
                            <svg viewBox="0 0 401 398.99" xmlns="http://www.w3.org/2000/svg">
                                <path transform="translate(0)" d="M370.11,250.39a10,10,0,0,0-10,10v88.68a30,30,0,0,1-30,30H49.94a30,30,0,0,1-30-30V88.8a30,30,0,0,1,30-30h88.67a10,10,0,1,0,0-20H49.94A50,50,0,0,0,0,88.8V349.05A50,50,0,0,0,49.94,399H330.16a50,50,0,0,0,49.93-49.94V260.37a10,10,0,0,0-10-10"></path>
                                <path transform="translate(0)" d="M376.14,13.16a45,45,0,0,0-63.56,0L134.41,191.34a10,10,0,0,0-2.57,4.39l-23.43,84.59a10,10,0,0,0,12.29,12.3l84.59-23.44a10,10,0,0,0,4.4-2.56L387.86,88.44a45,45,0,0,0,0-63.56Zm-220,184.67L302,52l47,47L203.19,244.86Zm-9.4,18.85,37.58,37.58-52,14.39Zm227-142.36-10.6,10.59-47-47,10.6-10.59a25,25,0,0,1,35.3,0L373.74,39a25,25,0,0,1,0,35.31"></path>
                            </svg>
                        </div>
                        <input type="file" id="banner-input" name="banner" class="d-none">
                    </div>

                    {{-- avatar, name and buttons --}}
                    <div class="main-info-cotainer">
                        {{-- avatar and name --}}
                        <div class="avatar-name-container show-uploaded-file-preview" data-trigger="#avatar-input">
                            <div class="profile-ava custom-file-preview" style="background-image:url({{userAvatar()}})">
                                <div class="img-edit-btn">
                                    <svg viewBox="0 0 401 398.99" xmlns="http://www.w3.org/2000/svg">
                                        <path transform="translate(0)" d="M370.11,250.39a10,10,0,0,0-10,10v88.68a30,30,0,0,1-30,30H49.94a30,30,0,0,1-30-30V88.8a30,30,0,0,1,30-30h88.67a10,10,0,1,0,0-20H49.94A50,50,0,0,0,0,88.8V349.05A50,50,0,0,0,49.94,399H330.16a50,50,0,0,0,49.93-49.94V260.37a10,10,0,0,0-10-10"></path>
                                        <path transform="translate(0)" d="M376.14,13.16a45,45,0,0,0-63.56,0L134.41,191.34a10,10,0,0,0-2.57,4.39l-23.43,84.59a10,10,0,0,0,12.29,12.3l84.59-23.44a10,10,0,0,0,4.4-2.56L387.86,88.44a45,45,0,0,0,0-63.56Zm-220,184.67L302,52l47,47L203.19,244.86Zm-9.4,18.85,37.58,37.58-52,14.39Zm227-142.36-10.6,10.59-47-47,10.6-10.59a25,25,0,0,1,35.3,0L373.74,39a25,25,0,0,1,0,35.31"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="name-container">
                                <input type="text" class="input" name="name" value="{{$currentUser->name}}">
                                <div data-input="name" class="form-error"></div>
                            </div>
                            <input type="file" id="avatar-input" name="avatar" class="d-none">
                        </div>
                        <div>
                            <a href="{{route('users.show', $currentUser)}}" class="view-as-member" style="display: block">
                                @lang('ui.viewAsmember')
                            </a>
                        </div>
                    </div>
                </section>

                <section class="u-p-section creads-section">
                    <div>
                        <label class="label">
                            Login
                            <span class="orange">*</span>
                        </label>
                        <input type="email" name="email" class="input" value="{{$currentUser->email}}">
                        <div data-input="email" class="form-error"></div>
                    </div>
                    <div>
                        <label class="label">Phone</label>
                        <input type="text" name="phone" class="input" value="{{$currentUser->phone}}">
                        <div data-input="phone" class="form-error"></div>
                    </div>
                </section>

                <section class="u-p-section bio-section">
                    {{-- bio --}}
                    <div class="bio-container">
                        <p class="bio-header">@lang('ui.bio')</p>
                        <textarea name="bio" class="bio-content" placeholder="@lang('ui.bioPh')">{{$currentUser->bio}}</textarea>
                    </div>

                    {{-- sidebar --}}
                    <div class="prod-side">
                        <div class="prod-info">
                            <div class="prod-info-item">
                                <div class="prod-info-name">@lang('ui.website')</div>
                                <div class="prod-info-text">
                                    <input type="text" name="website" class="input" placeholder="@lang('ui.websitePh')" value="{{$currentUser->website}}">
                                </div>
                            </div>

                            <div class="prod-info-item">
                                <div class="prod-info-name">@lang('ui.location')</div>
                                <div class="prod-info-text">
                                    <select class="select2" name="country">
                                        @foreach (countries() as $key => $name)
                                            <option value="{{$key}}" @selected($currentUser->country == $key)>{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="prod-info-item">
                                <div class="prod-info-name">Facebook</div>
                                <div class="prod-info-text">
                                    <input type="text" name="facebook" class="input" placeholder="@lang('ui.facebookPh')" value="{{$currentUser->facebook}}">
                                </div>
                            </div>

                            <div class="prod-info-item">
                                <div class="prod-info-name">LinkedIn</div>
                                <div class="prod-info-text">
                                    <input type="text" name="linkedin" class="input" placeholder="@lang('ui.linkedinPh')" value="{{$currentUser->linkedin}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="creads-section">
                    <div>
                        <button type="submit" class="button">@lang('ui.save')</button>
                    </div>
                    <div>
                        <a href="{{route('profile.password-form')}}" class="button" style="display: block">@lang('ui.editPassword')</a>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
