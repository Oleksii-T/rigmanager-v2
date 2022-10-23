@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.user.post.edit-trans')}}</title>
	<meta name="description" content="{{__('meta.description.user.post.edit-trans')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    @if (isset($post))
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemprop="item" href="{{route('profile.posts')}}"><span itemprop="name">{{__('ui.myPosts')}}</span></a>
            <meta itemprop="position" content="2" />
        </li>
        <li class="crop-bc-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemprop="item" href="{{route('posts.show', $post)}}"><span itemprop="name">{{$post->title_localed}}</span></a>
            <meta itemprop="position" content="3" />
        </li>
		<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemprop="item" href="{{route('posts.edit', $post)}}"><span itemprop="name">{{__('ui.postSettings')}}</span></a>
            <meta itemprop="position" content="4" />
        </li>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name">{{__('ui.postTransSettings')}}</span>
            <meta itemprop="position" content="5" />
        </li>
    @endif
@endsection

@section('content')
	<div class="main-block">
		<div class="content">
			<h1>{{__('ui.postTransSettings')}}</h1>
			<div class="content-top-text">{{__('ui.postTransSettingsHelp')}} <a href="{{route('faq', ['question'=>'auto-translator'])}}">{{__('ui.here')}}</a>.
                <span style="font-weight:500">{{__('ui.trasnlationEditWarning')}}</span></div>
            <div class="form-block">
                <form method="POST" action="{{route('posts.translations.update', $post)}}" class="general-ajax-submit">
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
                                <input class="input input-long" name="title[{{$localeCode}}]" type="text" placeholder="{{__('ui.enTitle')}}" value="{{$post->translated('title', $localeCode)}}" @disabled($post->auto_translate)/>
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
                            <button type="submit" class="button {{$post->auto_translate ? 'd-none' : ''}}">{{__('ui.saveChanges')}}</button>
                            <a class="button {{$post->auto_translate ? '' : 'd-none'}}" href="{{route('posts.show', $post)}}">@lang('ui.back')</a>
                        </div>
                    </fieldset>
                </form>
		    </div>
		</div>
	</div>
@endsection
