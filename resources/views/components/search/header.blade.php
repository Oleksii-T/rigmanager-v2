@php
    $isService = $filters['group'] == \App\Enums\PostGroup::SERVICE;
    $title = trans($isService ? 'ui.seCatalog' : 'ui.eqCatalog')
@endphp

<div class="search-page-title-block">
    <h1>
        @if (isset($filters['author']))
            {{$filters['author_name']}}
        @elseif (isset($filters['search']))
            "{{$filters['search']}}"
        @elseif (isset($category))
            {{$category->name}} @lang('ui.forSale')
        @else
            {{$title}}
        @endif
    </h1>
    @if (isset($filters['author']))
        <span class="link-w-icon">
            <a href="{{$filters['author_profile_link']}}">@lang('ui.profile')</a>
            <img src="{{asset('icons/link.svg')}}" alt="">
        </span>
    @endif
    <span class="searched-amount-wrpr">
        (<span class="orange searched-amount">_</span>)
    </span>
</div>
