@extends('layouts.app')

@section('meta')
    <title>@lang('meta.title.home')</title>
    <meta name="description" content="@lang('meta.description.home')">
    <meta name="robots" content="index, follow">
@endsection

@section('page-content')
    <div class="header-main">
        <x-header/>
        <section class="top-section">
            <div class="holder">
                <h1>@lang('ui.introduction')</h1>
                <div class="top-links">
                    <div class="top-links-item">
                        <a href="{{route('search', ['types'=>['sell', 'lease']])}}">@lang('ui.introSellEq')</a>
                    </div>
                    <div class="top-links-item">
                        <a href="{{route('search', ['types'=>['buy', 'rent']])}}">@lang('ui.introBuyEq')</a>
                    </div>
                </div>
                <div class="top-form">
                    <form action="{{route('search')}}">
                        <fieldset>
                            <div class="top-form-line">
                                <input type="text" class="input" name="search" placeholder="@lang('ui.search')" required>
                                <button class="button">@lang('ui.search')</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <section class="main-section">
        <div class="holder">
            <div class="main-category">
                <div id="tab1" class="tab-content">
                    <div class="main-category-block">
                        @foreach ($categoriesColumns as $column)
                            <ul class="main-category-col">
                                @foreach ($column as $c)
                                    <li><a href="{{route('search.category', $c)}}">{{$c->name}}</a></li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="brand-line">
                <div class="brand-slider">
                    @foreach ($partners as $partner)
                        <div class="brand-slide">
                            <a href="{{$partner->url??''}}" class="brand-item {{$partner->link ? 'brand-valid' : 'block-link'}}">
                                <img src="{{$partner->image->url}}" alt="{{$partner->image->alt}}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="ad-section">
                <h2>@lang('ui.newPosts')</h2>
                <div class="ad-list">
                    {{-- $posts --}}
                    <x-home-items :posts="$newPosts" />
                    <div class="ad-col ad-col-more">
                        <a href="#" class="ad-more">@lang('ui.morePosts')</a>
                    </div>
                </div>
            </div>
            @if ($urgentPosts->isNotEmpty())
                <div class="ad-section">
                    <h2>@lang('ui.urgentPosts')</h2>
                    <div class="ad-list">
                        <x-home-items :posts="$urgentPosts" />
                    </div>
                </div>
            @endif
        </div>
    </section>
    <section class="main-about">
        <div class="holder">
            <div class="main-about-block">
                <div class="main-about-logo">
                    <img src="{{asset('icons/logo-big.svg')}}" alt="">
                </div>
                <p>@lang('ui.epilogue1')</p>
                <p>@lang('ui.epilogue2')</p>
            </div>
        </div>
    </section>
@endsection
