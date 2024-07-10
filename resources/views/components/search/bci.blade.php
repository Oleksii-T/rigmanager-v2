@php
    $isService = $filters['group'] == \App\Enums\PostGroup::SERVICE;
    $title = trans($isService ? 'ui.seCatalog' : 'ui.eqCatalog')
@endphp

@if (isset($category))
    <x-bci :text="$title" :href="route('search')" i="2" />
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
    <x-bci :text="$title" :href="route('search')" i="2" :islast="!isset($filters['author']) && !isset($filters['search'])" />
    @if (isset($filters['author']))
        <x-bci :text="$filters['author_name']" :href="$filters['author']" i="3" islast="1" />
    @endif
    @if (isset($filters['search']))
        <x-bci text="'{{$filters['search']}}'" :href="$filters['search']" i="3" islast="1" />
    @endif
@endif
