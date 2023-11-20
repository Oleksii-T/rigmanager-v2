@extends('layouts.page')

@section('meta')
    <title>{{__('meta.title.info.contacts')}}</title>
    <meta name="description" content="{{__('meta.description.info.contacts')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.footerContact')" i="2" islast="1" />
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='contact'/>

		<div class="content">
			<h1>{{__('ui.footerContact')}}</h1>
			<div class="content-top-text">{{__('ui.contactsFooter')}}

				{{env('MAIL_TO_ADDRESS')}}
				{{env('CONTACT_PHONE')}}</div>
			<div class="form-block">
				<form id="form-contact" method="POST" action="{{route('feedbacks.store')}}" class="general-ajax-submit with-recaptcha">
					@csrf
                    <fieldset>
						<label class="label">{{__('ui.userName')}} <span class="orange">*</span></label>
						<input type="text" class="input" name="name" value="{{$currentUser->name??null}}">
                        <div data-input="name" class="form-error"></div>

						<label class="label">{{__('ui.fromUserEmail')}} <span class="orange">*</span></label>
						<input type="text" class="input" name="email" value="{{$currentUser->email??null}}">
                        <div data-input="email" class="form-error"></div>

						<label class="label">{{__('ui.fromUserSubject')}} <span class="orange">*</span></label>
						<input type="text" class="input" name="subject">
                        <div data-input="subject" class="form-error"></div>

						<label class="label">{{__('ui.fromUserText')}} <span class="orange">*</span></label>
						<textarea cols="30" rows="10" maxlength="2000" name="text" class="textarea" placeholder="{{__('ui.fromUserTextPlaceholder')}}"></textarea>
                        <div data-input="text" class="form-error"></div>

						<div class="form-button">
							<button type="submit" class="button">{{__('ui.fromUserSubmit')}}</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
@endsection
