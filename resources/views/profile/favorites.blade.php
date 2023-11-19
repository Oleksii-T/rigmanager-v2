@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.post.favs')</title>
	<meta name="description" content="@lang('meta.description.user.post.favs')">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    @if ($category)
        <x-bci :text="trans('ui.favourites')" i="2" :href="route('profile.favourites')" />
        @foreach ($category?->parents(true)??[] as $category)
            <x-bci
                :text="$category->name"
                :href="route('profile.favourites', $category->parents(true))"
                :i="$loop->index + 3"
                :islast="$loop->last"
            />
        @endforeach
    @else
        <x-bci :text="trans('ui.favourites')" i="2" islast="1" />
    @endif
@endsection

@section('style')
    <style>
        /* filters */
        .form-block {
            background: transparent;
            padding: 4px 50px 2px;
            margin: 0px;
            border-radius: 0px;
        }
        .sorting {
            margin: 0px;
        }
    </style>
@endsection

@section('content')
    <span class="hidden" data-categoryid="{{$category->id??null}}" page-data></span>
    <div class="main-block">
        <x-profile.nav active='fav' />
        <div class="content">
            <h1>
                @lang('ui.favourites')
                (<span class="orange searched-amount">_</span>)
            </h1>
            <div class="faq-item optionals" style="margin-bottom: 14px">
                <a href="" class="faq-top">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 255.99 511.99">
                        <path d="M253,248.62,18.37,3.29A10.67,10.67,0,1,0,3,18L230.56,256,3,494A10.67,10.67,0,0,0,18.37,508.7L253,263.37A10.7,10.7,0,0,0,253,248.62Z"/>
                    </svg>
                    <span class="text-show">Filters</span>
                </a>
                <div class="faq-hidden form-block">
                    <div class="row filter-block">
                        <x-profile.post-filters ttt="favorites" />

                        <div class="col-12">
                            <div class="searched-categories-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="searched-content"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/posts-filter.js')}}"></script>
    <script src="{{asset('js/posts.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.trigger-show-filters').click(function(e) {
                e.preventDefault();
                let wraper = $('.filters-dropdown');
                if (wraper.hasClass('active')) {
                    $('.filter-block').slideUp();
                } else {
                    $('.filter-block').slideDown();
                }
                wraper.toggleClass('active');
            })
        });
    </script>
@endsection
