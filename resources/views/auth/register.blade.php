@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.auth.reg')}}</title>
	<meta name="description" content="{{__('meta.description.auth.reg')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">{{__('ui.signUp')}}</span>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    <div class="reg">
        <div class="reg-content">
            <h1>{{__('ui.signUp')}}</h1>
            <form id="form-signup" method="POST" action="{{loc_url(route('register'))}}">
                @csrf
                <fieldset>
                    <div class="reg-wrap">
                        <div class="reg-col">
                            <label class="label">{{__('ui.login')}} <span class="orange">*</span></label>
                            <input class="input" type="text" name="email" value="{{old('email')}}" required autocomplete="email" placeholder="mail.mail@mail.com">
                            <x-server-input-error inputName='email'/>
                            <div class="form-note">{{__('ui.loginHelp')}}</div>
                        </div>
                        <div class="reg-col">
                            <label class="label">{{__('ui.phone')}}</label>
                            <input class="input format-phone" type="text" name="phone_raw" value="{{old('phone_raw')}}" autocomplete="phone" placeholder="+38 ( _ _ _ ) _ _ _ - _ _ - _ _">
                            <x-server-input-error inputName='phone_raw'/>
                            <div class="form-note">{{__('ui.phoneHelp')}}</div>
                        </div>
                        <div class="reg-col">
                            <label class="label">{{__('ui.userName')}} <span class="orange">*</span></label>
                            <input class="input" type="text" name="name" value="{{old('name')}}" required autocomplete="name" placeholder="{{__('ui.userName')}}">
                            <x-server-input-error inputName='name'/>
                            <div class="form-note">{{__('ui.userNameHelp')}}</div>
                        </div>
                    </div>
                    <div class="reg-wrap">
                        <div class="reg-col">
                            <label class="label">{{__('ui.password')}} <span class="orange">*</span></label>
                            <input class="input" id="password" type="password" name="password" required autocomplete="new-password" placeholder="{{__('ui.password')}}">
                            <x-server-input-error inputName='password'/>
                            <div class="form-note">{{__('ui.passwordHelp')}}</div>
                        </div>
                        <div class="reg-col">
                            <label class="label">{{__('ui.rePass')}} <span class="orange">*</span></label>
                            <input class="input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{__('ui.rePass')}}">
                            <x-server-input-error inputName='password_confirmation'/>
                            <div class="form-note">{{__('ui.rePassHelp')}}</div>
                        </div>
                        <div class="reg-col">
                            <div class="check-block">
                                <div class="check-item">
                                    <input type="checkbox" class="check-input" id="ch1" name="agreement">
                                    <label for="ch1" class="check-label">{{__('ui.iAgree')}} «<a href="{{loc_url(route('terms'))}}">{{__('ui.iAgreeLink')}}</a>»</label>
                                </div>
                            </div>
                            <x-server-input-error inputName='agreement'/>
                            <button class="button">{{__('ui.makeSignUp')}}</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="reg-side">
            <div class="reg-social-title">{{__('ui.socialSignInTitle')}}</div>
            <div class="reg-social-text">{{__('ui.socialSignIn')}}</div>
            <div class="social-buttons">
                <a href="{{route('login.social', ['social'=>'facebook'])}}" class="social-fb"><img src="{{asset('icons/fb.svg')}}" alt=""></a>
                <a href="{{route('login.social', ['social'=>'google'])}}" class="social-google"><img src="{{asset('icons/google.svg')}}" alt=""></a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // change default error-lable insertion location
            $.validator.setDefaults({
                errorPlacement: function(error, element) {
                    if (element.prop('name') === 'agreement') {
                        error.insertAfter(element.parent().parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            //Validate the form
            $('#form-signup').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 40,
                        validName: true,
                        remote: '{{ route('username.exist') }}',
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: '{{ route('email.exist') }}',
                        maxlength: 254
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        validPassword: true,
                        maxlength: 20
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    },
                    agreement: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: '{{ __("validation.required") }}',
                        minlength: '{{ __("validation.min.string", ["min" => 3]) }}',
                        maxlength: '{{ __("validation.max.string", ["max" => 40]) }}',
                        remote: '{{ __("validation.unique-username") }}',
                        validName: '{{__("validation.username")}}'
                    },
                    email: {
                        required: '{{ __("validation.required") }}',
                        remote: '{{ __("validation.unique-email") }}',
                        email: '{{ __("validation.email") }}',
                        maxlength: '{{ __("validation.max.string", ["max" => 254]) }}'
                    },
                    password: {
                        required: '{{ __("validation.required") }}',
                        minlength: '{{ __("validation.min.string", ["min" => 6]) }}',
                        maxlength: '{{ __("validation.max.string", ["max" => 20]) }}',
                        validPassword: '{{__("validation.password")}}'
                    },
                    password_confirmation: {
                        required: '{{ __("validation.required") }}',
                        equalTo: '{{ __("validation.passEqual") }}'
                    },
                    agreement: {
                        required: '{{ __("validation.agreement") }}'
                    }
                },
                errorElement: 'div',
				errorClass: 'form-error'
            });
        });
    </script>
@endsection
