@extends('layouts.page')

@section('meta')

@endsection

@section('bc')
    @if (isset($category))
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemprop="item" href="{{route('search')}}"><span itemprop="name">@lang('ui.catalog')</span></a>
            <meta itemprop="position" content="2" />
        </li>
        @foreach ($category->parents(true) as $category)
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                @if ($loop->last)
                    <span itemprop="name">{{$category->name}}</span>
                @else
                    <a itemprop="item" href="{{route('search.category', $category)}}"><span itemprop="name">{{$category->name}}</span></a>
                @endif
                <meta itemprop="position" content="{{$loop->index + 2}}" />
            </li>
        @endforeach
    @else
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name">@lang('ui.catalog')</span>
            <meta itemprop="position" content="2" />
        </li>
    @endif
@endsection

@section('content')
    <span class="hidden" data-category="{{isset($category) ? $category->id : ''}}" page-data></span>

    <div class="main-block">
        <aside class="side">
            <a data-fancybox class="side-mob">@lang('ui.filters')</a>
            <div class="filter-block">
                <div class="filter-title">@lang('ui.filters')</div>

                <!--currency-->
                <label class="label">@lang('ui.currency')</label>
                <div class="select-block">
                    <select class="styled" name="currency">
                        <option value="">{{__('ui.notSpecified')}}</option>
                        @foreach (currencies() as $key => $symbol)
                            <option value="{{$key}}">{{strtoupper($key)}}</option>
                        @endforeach
                    </select>
                </div>

                <!--cost-->
                <label class="label">@lang('ui.cost')</label>
                <div class="price-input">
                    <input type="text" class="input" name="cost_from" placeholder="@lang('ui.from')">
                    <span class="price-input-divider">-</span>
                    <input type="text" class="input" name="cost_to" placeholder="@lang('ui.to')">
                </div>

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

                <!--condition-->
                <label class="label">@lang('ui.condition')</label>
                <div id="condition" class="check-block">
                    @foreach (\App\Models\Post::CONDITIONS as $item)
                        <div class="check-item">
                            <input type="checkbox" class="check-input" name="conditions" value="{{$item}}" id="{{$item}}" @checked(in_array($item, request()->conditions??[]))>
                            <label for="{{$item}}" class="check-label">{{\App\Models\Post::conditionReadable($item)}}</label>
                        </div>
                    @endforeach
                </div>

                <!--type-->
                <label class="label">@lang('ui.postType')</label>
                <div id="type" class="check-block">
                    @foreach (\App\Models\Post::TYPES as $item)
                        <div class="check-item">
                            <input type="checkbox" class="check-input" name="types" id="{{$item}}" value="{{$item}}" @checked(in_array($item, request()->types??[]))>
                            <label for="{{$item}}" class="check-label">{{\App\Models\Post::typeReadable($item)}}</label>
                        </div>
                    @endforeach
                </div>

                <!--urgent-->
                <label class="label">@lang('ui.urgent')</label>
                <div id="urgent" class="check-block">
                    <div class="check-item">
                        <input type="checkbox" class="check-input" name="is_urgent" id="is-urgent-1" value="1" @checked(in_array($item, request()->is_urgent??[]))>
                        <label for="is-urgent-1" class="check-label">@lang('ui.yes')</label>
                    </div>
                    <div class="check-item">
                        <input type="checkbox" class="check-input"  name="is_urgent" id="is-urgent-0" value="0" @checked(in_array($item, request()->is_urgent??[]))>
                        <label for="is-urgent-0" class="check-label">@lang('ui.no')</label>
                    </div>
                </div>

                <!--sorting-->
                <label class="label">@lang('ui.sort')</label>
                <div class="radio-block filter-sorting">
                    @foreach (\App\Models\Post::getSorts() as $key => $name)
                        <div class="radio-item">
                            <input type="radio" name="sorting" class="radio-input" id="{{$key}}" value="{{$key}}" @checked($loop->first)>
                            <label for="{{$key}}" class="radio-label">{{$name}}</label>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="side-add">
                <div class="side-add-text">@lang('ui.mailerSuggestText')</div>
                <div class="side-add-icon"><img src="{{asset('icons/add-icon.svg')}}" alt=""></div>
                <button data-url="{{route('mailers.store')}}" class="button add-request-to-mailer">@lang('ui.add')</button>
            </div>
        </aside>
        <div class="content">
            <h1>
                @if (isset($category))
                    {{$category->name}}
                @else
                    @lang('ui.catalog')
                @endif
                (<span class="orange searched-amount">{{$posts->total()}}</span>)
            </h1>
            @if ($posts->isEmpty())
                <div class="searched-empty">
                    <p>@lang('ui.searchFail'). <a href="{{url()->previous()}}">@lang('ui.serverErrorGoBack')</a></p>
                </div>
            @else
                <x-search.categories :categories="isset($category) ? $category->childs()->active() : \App\Models\Category::active()->whereNull('category_id')"/>
                <div class="searched-content">
                    <x-search.items :posts="$posts"/>
                </div>
            @endif
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
