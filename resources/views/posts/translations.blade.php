@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.user.post.edit-trans')}}</title>
	<meta name="description" content="{{__('meta.description.user.post.edit-trans')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    @if (isset($post))
        <x-bci :text="trans('ui.myPosts')" :href="route('profile.posts')" i="2"/>
        <x-bci :text="$post->title" :href="route('posts.show', $post)" i="3"/>
        <x-bci :text="trans('ui.postSettings')" :href="route('posts.edit', $post)" i="4"/>
        <x-bci :text="trans('ui.postTransSettings')" i="5" islast="1"/>
    @endif
@endsection

@section('content')
	<div class="main-block">
		<div class="content">
			<h1>{{__('ui.postTransSettings')}}</h1>
			<div class="content-top-text">{{__('ui.postTransSettingsHelp')}} <a href="{{route('faq', ['question'=>'auto-translator'])}}">{{__('ui.here')}}</a>.
                <span style="font-weight:500">{{__('ui.trasnlationEditWarning')}}</span>
            </div>
            <form action="{{route('posts.translations.report', $post)}}" method="post" class="general-ajax-submit ask" data-asktitle="Invalid translations?" data-asktext="Report invalid translations to site moderators?" data-askno="Cancel" data-askyes="Yes, report it">{{-- //! TRANSLATE --}}
                @csrf
                <button type="submit" class="button">{{__('ui.posts.my-translations-is-invalid')}}</button>
            </form>
            <div class="form-block">
                <form method="POST" action="{{route('posts.translations.update', $post)}}" class="post-translations" data-asktitle="Re-run Auto-Translator?" data-asktext="Do you want to re-run Auto-Translator based on your original title and description?" data-askno="No" data-askyes="Yes, update my translations!">{{-- //! TRANSLATE --}}
                    @method('PUT')
                    @csrf
                    <div class="form-section">
                        <label class="label">@lang('ui.posts.use-auto-translate')</label>
                        <div class="check-block">
                            <div class="check-item">
                                <input type="checkbox" name="auto_translate" class="check-input post-auto-translate-toggle" id="auto_translate" value="1" @checked($post->auto_translate)>
                                <label for="auto_translate" class="check-label">@lang('ui.yes')</label>
                            </div>
                            <div data-input="auto_translate" class="form-error"></div>
                        </div>
                    </div>
                    <fieldset>
                        <div class="form-section"> <!--title-->
                            <label class="label">{{__('ui.originalTitle')}} (<span class="orange">{{$post->origin_lang}}</span>)</label>
                            <p class="fake-input">{{$post->translated('title', $post->origin_lang)}}</p>

                            @foreach(LaravelLocalization::getLocalesOrder() as $localeCode => $properties)
                                @if ($post->origin_lang == $localeCode)
                                    @continue
                                @endif
                                <label class="label">@lang('ui.posts.titleInLang') {{$properties['name']}} <span class="orange">*</span></label>
                                <input class="input input-long" name="title[{{$localeCode}}]" type="text" value="{{$post->translated('title', $localeCode)}}" @disabled($post->auto_translate)/>
                                <div data-input="title[{{$localeCode}}]" class="form-error"></div>
                            @endforeach
                        </div>
                        <div class="form-section"> <!--description-->
                            <label class="label">{{__('ui.originalDescription')}} (<span class="orange">{{$post->origin_lang}}</span>)</label>
                            <p class="fake-input">{{$post->translated('description', $post->origin_lang)}}</p>

                            @foreach(LaravelLocalization::getLocalesOrder() as $localeCode => $properties)
                                @if ($post->origin_lang == $localeCode)
                                    @continue
                                @endif
                                <label class="label">@lang('ui.posts.descriptionInLang') {{$properties['name']}} <span class="orange">*</span></label>
                                <textarea cols="30" rows="10" maxlength="9000" class="textarea" name="description[{{$localeCode}}]" @disabled($post->auto_translate)>{{$post->translated('description', $localeCode)}}</textarea>
                            @endforeach
                        </div>
                        <div class="form-button-block">
                            <button type="submit" class="button">{{__('ui.saveChanges')}}</button>
                        </div>
                    </fieldset>
                </form>
		    </div>
		</div>
	</div>
@endsection

@section('scripts')
    <script src="{{asset('js/posts.js')}}"></script>
@endsection
