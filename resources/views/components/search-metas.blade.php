@if (isset($category))
    @if (isset($filters['author']))
        <title>{{$filters['author']}} {{$category->name}} @lang('ui.equipmentForSale') | rigmanagers.com</title>
        <meta name="description" content="@lang('ui.findNewOrUsed') {{$filters['author']}} {{$category->name}} @lang('ui.eqForSaleOrRent')">
    @else
        <title>{{$category->meta_title}}</title>
        <meta name="description" content="@lang('ui.findNewOrUsed') {{$category->name}} @lang('ui.eqForSaleOrRent')">
    @endif
@else
    @if (isset($filters['author']))
        <title>{{$filters['author']}} @lang('ui.equipmentForSale') | rigmanagers.com</title>
        <meta name="description" content="@lang('ui.findNewOrUsed') {{$filters['author']}} @lang('ui.eqForSaleOrRent')">
    @else
        <title>@lang('meta.title.search.list')</title>
        <meta name="description" content="@lang('meta.description.search.list')">
    @endif
@endif