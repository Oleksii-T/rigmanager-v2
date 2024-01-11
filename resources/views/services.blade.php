@extends('layouts.page')

@section('meta')

@endsection

@section('bc')
    @include('components.search-bci')
@endsection

@section('content')
    <span class="hidden" data-category="{{isset($category) ? $category->id : ''}}" page-data></span>

    <div class="main-block">
        <aside class="side">
            <a data-fancybox class="side-mob">@lang('ui.filters')</a>
            <div class="filter-block">
                <div class="filter-title">@lang('ui.filters')</div>

                <!--country-->
                <label class="label">@lang('ui.country')</label>
                <div class="select-block">
                    <select class="styled" name="country">
                        <option value="0">{{__('ui.notSpecified')}}</option>
                        @foreach (\App\Models\Post::countries() as $key => $name)
                            <option value="{{$key}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>

                <!--type-->
                <label class="label">@lang('ui.postType')</label>
                <div id="type" class="check-block">
                    @foreach (\App\Enums\PostType::forService() as $key => $name)
                        <div class="check-item">
                            <input type="checkbox" class="check-input" name="types" id="type-{{$key}}" value="{{$key}}" @checked(in_array($key, request()->types??[]))>
                            <label for="type-{{$key}}" class="check-label">{{$name}}</label>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="side-add" id="add-to-mailer-ad">
                <div class="side-add-text">@lang('ui.mailerSuggestText')</div>
                <div class="side-add-icon"><img src="{{asset('icons/add-icon.svg')}}" alt=""></div>
                <button data-url="{{route('mailers.store')}}" class="button add-request-to-mailer">@lang('ui.add')</button>
            </div>
        </aside>
        <div class="content">
            @include('components.search-header')
            <div class="searched-categories-content"></div>
            <div class="searched-content"></div>
            <div class="searched-loading hidden">
                <img src="{{asset('icons/loading.svg')}}" alt="">
            </div>
            <div class="searched-empty hidden">
                <p>@lang('ui.searchFail'). <a href="{{ url()->previous() }}">@lang('ui.serverErrorGoBack')</a></p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/posts-filter.js')}}"></script>
@endsection
