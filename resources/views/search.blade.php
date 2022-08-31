@extends('layouts.page')

@section('meta')

@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{route('categories')}}"><span itemprop="name">@lang('ui.catalog')</span></a>
        <meta itemprop="position" content="2" />
    </li>
    todo
@endsection

@section('content')
    <div class="main-block">
        <aside class="side">
            <a href="#filter-block" data-fancybox class="side-mob">@lang('ui.filters')</a>
            <div class="filter-block" id="filter-block">
                <div class="filter-title">@lang('ui.filters')</div>

                <!--cost-->
                <label class="label">
                    @lang('ui.cost'),
                    <div class="tumbler-inline">
                        <div class="tumbler">
                            <a href="" class="tumbler-left currency-switch uah active">UAH</a>
                            <span class="tumbler-block"></span>
                            <a href="" class="tumbler-right currency-switch usd">USD</a>
                        </div>
                    </div>
                </label>
                <div class="price-input">
                    <input type="text" class="input" name="cost_from" placeholder="@lang('ui.from')">
                    <span class="price-input-divider">-</span>
                    <input type="text" class="input" name="cost_to" placeholder="@lang('ui.to')">
                </div>

                <!--region-->
                <label class="label">@lang('ui.region')</label>
                <div class="select-block">
                    {{-- <x-region-select locale='{{app()->getLocale()}}'/> --}}
                    todo
                </div>

                <!--condition-->
                <label class="label">@lang('ui.condition')</label>
                <div id="condition" class="check-block">
                    @foreach (\App\Models\Post::CONDITIONS as $item)
                        <div class="check-item">
                            <input type="checkbox" class="check-input" name="conditions" value="{{$item}}" id="{{$item}}">
                            <label for="{{$item}}" class="check-label">{{\App\Models\Post::conditionReadable($item)}}</label>
                        </div>
                    @endforeach
                </div>

                <!--type-->
                <label class="label">@lang('ui.postType')</label>
                <div id="type" class="check-block">
                    @foreach (\App\Models\Post::TYPES as $item)
                        <div class="check-item">
                            <input type="checkbox" class="check-input" name="types" id="{{$item}}" value="{{$item}}">
                            <label for="{{$item}}" class="check-label">{{\App\Models\Post::typeReadable($item)}}</label>
                        </div>
                    @endforeach
                </div>

                <!--legal-->
                <label class="label">@lang('ui.postRole')</label>
                <div id="role" class="check-block">
                    @foreach (\App\Models\Post::LEGAL_TYPES as $item)
                        <div class="check-item">
                            <input type="checkbox" class="check-input" name="legal_types" id="{{$item}}" value="{{$item}}">
                            <label for="{{$item}}" class="check-label">{{\App\Models\Post::legalTypeReadable($item)}}</label>
                        </div>
                    @endforeach
                </div>

                <!--urgent-->
                <label class="label">@lang('ui.urgent')</label>
                <div id="urgent" class="check-block">
                    <div class="check-item">
                        <input type="checkbox" class="check-input" name="is_urgent" id="is-urgent-1" value="1">
                        <label for="is-urgent-1" class="check-label">@lang('ui.yes')</label>
                    </div>
                    <div class="check-item">
                        <input type="checkbox" class="check-input"  name="is_urgent" id="is-urgent-0" value="0">
                        <label for="is-urgent-0" class="check-label">@lang('ui.no')</label>
                    </div>
                </div>

                <!--import-->
                <label class="label">@lang('ui.importExport')</label>
                <div id="import" class="check-block">
                    <div class="check-item">
                        <input type="checkbox" class="check-input" name="is_import" id="is-import-1" value="1">
                        <label for="is-import-1" class="check-label">@lang('ui.yes')</label>
                    </div>
                    <div class="check-item">
                        <input type="checkbox" class="check-input" name="is_import" id="is-import-0" value="0">
                        <label for="is-import-0" class="check-label">@lang('ui.no')</label>
                    </div>
                </div>

                <!--sorting-->
                <label class="label">@lang('ui.sort')</label>
                <div class="radio-block filter-sorting">
                    <div class="radio-item">
                        <input type="radio" name="sorting" class="radio-input" id="latest" value="latest" checked>
                        <label for="latest" class="radio-label">@lang('ui.sortNew')</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" name="sorting" class="radio-input" id="cheapest" value="cheapest">
                        <label for="cheapest" class="radio-label">@lang('ui.sortCheap')</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" name="sorting" class="radio-input" id="expensive" value="expensive">
                        <label for="expensive" class="radio-label">@lang('ui.sortExpensive')</label>
                    </div>
                    {{-- @if (auth()->check() && auth()->user()->is_pro) --}}
                        <div class="radio-item">
                            <input type="radio" name="sorting" class="radio-input" id="popular" value="popular">
                            <label for="popular" class="radio-label">@lang('ui.sortViews')</label>
                        </div>
                    {{-- @endif --}}
                </div>

            </div>
            <div class="side-add">
                <div class="side-add-text">@lang('ui.mailerSuggestText')</div>
                <div class="side-add-icon"><img src="{{asset('icons/add-icon.svg')}}" alt=""></div>
                <a href="" class="button add-request-to-mailer">@lang('ui.add')</a>
            </div>
        </aside>
        <div class="content">
            <h1>
                @if (isset($category))
                    {{$category->name}}
                @else

                @endif
                (<span class="orange searched-amount">{{$posts->total()}}</span>)
            </h1>
            @if ($posts->isEmpty())
                <div class="searched-empty">
                    <p>@lang('ui.searchFail'). <a href="{{url()->previous()}}">@lang('ui.serverErrorGoBack')</a></p>
                </div>
            @else
                @if (isset($category))
                    <x-search.categories :category="$category"/>
                @endif
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
    <script src="{{asset('js/search.js')}}"></script>
@endsection
