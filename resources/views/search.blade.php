@extends('layouts.page')

@section('meta')

@endsection

@section('bc')
    @if (isset($category))
        <x-bci :text="trans('ui.catalog')" :href="route('search')" i="2" />
        @if (isset($filters['search']))
            <x-bci text="'{{$filters['search']}}'" :href="route('search', ['search'=>$filters['search']])" i="3" />
        @endif
        @if (isset($filters['author']))
            <x-bci :text="$filters['author_name']" :href="route('search', ['author'=>$filters['author']])" i="3" />
        @endif
        @foreach ($category->parents(true) as $category)
            <x-bci
                :text="$category->name"
                :href="$category->getUrl()"
                :i="$loop->index + (isset($filters['author']) || isset($filters['search']) ? 4 : 3)"
                :islast="$loop->last"
            />
        @endforeach
    @else
        <x-bci :text="trans('ui.catalog')" :href="route('search')" i="2" :islast="!isset($filters['author']) && !isset($filters['search'])" />
        @if (isset($filters['author']))
            <x-bci :text="$filters['author_name']" :href="$filters['author']" i="3" islast="1" />
        @endif
        @if (isset($filters['search']))
            <x-bci text="'{{$filters['search']}}'" :href="$filters['search']" i="3" islast="1" />
        @endif
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
                            <option value="{{$key}}" @selected(request()->currency == $key)>{{strtoupper($key)}}</option>
                        @endforeach
                    </select>
                </div>

                <!--cost-->
                <label class="label">@lang('ui.cost')</label>
                <div class="price-input">
                    <input type="text" class="input" name="cost_from" placeholder="@lang('ui.from')" value="{{request()->cost_from}}">
                    <span class="price-input-divider">-</span>
                    <input type="text" class="input" name="cost_to" placeholder="@lang('ui.to')" value="{{request()->cost_to}}">
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
                        <input type="checkbox" class="check-input" name="is_urgent" id="is-urgent-1" value="1" @checked(in_array(1, request()->is_urgent??[]))>
                        <label for="is-urgent-1" class="check-label">@lang('ui.yes')</label>
                    </div>
                    <div class="check-item">
                        <input type="checkbox" class="check-input"  name="is_urgent" id="is-urgent-0" value="0" @checked(in_array(0, request()->is_urgent??[]))>
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
            <div class="side-add" id="add-to-mailer-ad">
                <div class="side-add-text">@lang('ui.mailerSuggestText')</div>
                <div class="side-add-icon"><img src="{{asset('icons/add-icon.svg')}}" alt=""></div>
                <button data-url="{{route('mailers.store')}}" class="button add-request-to-mailer">@lang('ui.add')</button>
            </div>
        </aside>
        <div class="content">
            <h1>
                @if (isset($filters['author']))
                    {{$filters['author_name']}}
                @elseif (isset($filters['search']))
                    "{{$filters['search']}}"
                @elseif (isset($category))
                    {{$category->name}}
                @else
                    @lang('ui.catalog')
                @endif
                (<span class="orange searched-amount">_</span>)
            </h1>
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
