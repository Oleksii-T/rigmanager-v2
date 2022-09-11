@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.edit')</title>
	<meta name="description" content="@lang('meta.description.user.edit')">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{route('profile.index')}}"><span itemprop="name">@lang('ui.profileInfo')</span></a>
        <meta itemprop="position" content="2" />
    </li>
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">@lang('ui.profileEditing')</span>
        <meta itemprop="position" content="3" />
    </li>
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='profile'/>
        <div class="content">
            <h1>@lang('ui.profileEditing')</h1>
            <div class="profile-edit">
                <div class="profile-edit-column">
                    <form action="{{route('profile.update')}}" method="POST" class="general-ajax-submit">
                        @csrf
                        @method('PUT')
                        <fieldset>
                            <div class="form-title">
                                @lang('ui.profileInfo')
                            </div>

                            <label class="label">@lang('ui.login') <span class="orange">*</span></label>
                            <input type="email" class="input" name="email" value="{{$currentUser->email}}" placeholder="@lang('ui.login')" {{$currentUser->is_social ? 'disabled' : ''}}>
                            <div data-input="email" class="form-error"></div>
                            <div class="form-note">@lang('ui.loginHelp')</div>

                            <label class="label">@lang('ui.userName') <span class="orange">*</span></label>
                            <input type="text" class="input" name="name" value="{{$currentUser->name}}" placeholder="@lang('ui.userName')">
                            <div data-input="name" class="form-error"></div>
                            <div class="form-note">@lang('ui.userNameHelp')</div>

                            <label class="label">@lang('ui.phone')</label>
                            <input type="tel" class="input format-phone" name="phone" value="{{$currentUser->phone_readable}}" placeholder="+38 ( _ _ _ ) _ _ _ - _ _ - _ _">
                            <div data-input="phone" class="form-error"></div>
                            <div class="form-note">@lang('ui.phoneHelp')</div>

                            <label class="label">@lang('ui.country')</label>
                            <select class="select2" name="country">
                                @foreach (countries() as $key => $name)
                                    <option value="{{$key}}" @selected($currentUser->country == $key)>{{$name}}</option>
                                @endforeach
                            </select>
                            <div data-input="country" class="form-error"></div>

                            <label class="label">@lang('ui.bio')</label>
                            <textarea name="bio">{{$currentUser->bio}}</textarea>
                            <div data-input="country" class="form-error"></div>

                            <div class="edit-ava show-uploaded-file-preview">
                                <img class="edit-ava-img custom-file-preview" src="{{$currentUser->avatar->url ?? asset('icons/emptyAva.svg')}}" alt="">
                                <div class="edit-ava-button">
                                    <input id="ava" type="file" name="avatar">
                                    <label for="ava" class="edit-ava-label">@lang('ui.changeProfilePic')</label>
                                </div>
                            </div>

                            <div class="form-button">
                                <button type="submit" class="button">@lang('ui.saveChanges')</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="profile-edit-column">
                    <form action="{{route('profile.password')}}" method="POST" class="general-ajax-submit">
                        @csrf
                        @method('PUT')
                        <fieldset>
                            <div class="form-title">@lang('ui.password')</div>

                            <label class="label">@lang('ui.curPass') <span class="orange">*</span></label>
                            <input type="password" name="current_password" class="input">
                            <div data-input="current_password" class="form-error"></div>

                            <label class="label">@lang('ui.newPass') <span class="orange">*</span></label>
                            <input type="password" name="password" id="password" class="input">
                            <div data-input="password" class="form-error"></div>
                            <div class="form-note">@lang('ui.passwordHelp')</div>

                            <label class="label">@lang('ui.reNewPass') <span class="orange">*</span></label>
                            <input type="password" name="password_confirmation" class="input">
                            <div class="form-note">@lang('ui.rePassHelp')</div>

                            <div class="form-button">
                                <button type="submit" class="button">@lang('ui.changePassword')</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
