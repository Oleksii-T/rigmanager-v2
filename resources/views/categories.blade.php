@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.catalog')</title>
	<meta name="description" content="@lang('meta.description.catalog')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.categories')" i="2" islast="1" />
@endsection

@section('content')
    <div class="main-block">
        <aside class="side">
            <a href="#side-block" data-fancybox class="side-mob">@lang('ui.catalogNav')</a>
            <div class="side-block" id="side-block">
                <div class="side-title">@lang('ui.catalogNav')</div>
                <ul class="side-list">
                    @foreach ($categories as $category)
                        <li><a href="{{$category->getUrl()}}">{{$category->name}}</a></li>
                    @endforeach
                </ul>
            </div>
        </aside>
        <div class="content">
            <h1>@lang('ui.categories')</h1>
            <div class="content-top-text catalog-help">@lang('ui.catalogHelp')</div>
            <div class="category">
                @foreach ($categories as $category)
                    <div class="category-col">
                        <div class="category-item">
                            @if ($category->image)
                                <div class="category-img">
                                    <a href="{{$category->url}}">
                                        <img src="{{$category->image->url}}" alt="{{$category->image->alt}}">
                                    </a>
                                </div>
                            @endif
                            <div class="category-name">
                                <a href="{{$category->url}}">
                                    {{$category->name}}
                                </a>
                                (<span class="orange">{{$category->posts_count}}</span>)
                            </div>
                            @if ($category->childs->isNotEmpty())
                                <ul class="category-list">
                                    @foreach ($category->childs as $childCat)
                                        <li>
                                            <a href="{{$childCat->url}}">
                                                {{$childCat->name}}
                                            </a>
                                            @if ($childCat->childs->isNotEmpty())
                                                @foreach ($childCat->childs as $childChildCat)
                                                    <ul class="category-sublist">
                                                        <li>
                                                            <a href="{{$childChildCat->url}}">
                                                                {{$childChildCat->name}}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endforeach
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
