@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.edit-password')</title>
	<meta name="description" content="@lang('meta.description.user.edit-password')">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.profileInfo')" i="2" :href="route('profile.index')"/>
    <x-bci :text="trans('ui.profileEditing')" i="2" islast="1"/>
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='profile'/>
        <div class="content">
            <h1>@lang('ui.profileEditing')</h1>
            <div class="profile-edit">
                <div class="profile-edit-column">
                    <form action="{{route('profile.password')}}" method="POST" class="general-ajax-submit">
                        @csrf
                        @method('PUT')
                        <fieldset>
                            <div class="form-title">
                                @lang('ui.password')
                                @if (!$currentUser->password)
                                    <br>
                                    <small style="font-size: 50%" class="orange">
                                        Your account do not have password (only {{$currentUser->socials()->first()->provider}} login)
                                    </small>
                                @endif
                            </div>

                            @if ($currentUser->password)
                                <label class="label">@lang('ui.curPass') <span class="orange">*</span></label>
                                <input type="password" name="current_password" class="input">
                                <div data-input="current_password" class="form-error"></div>
                            @endif

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
