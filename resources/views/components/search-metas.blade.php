@if (isset($category))
    @if (isset($filters['author']))
        <title>{{$filters['author']}} {{$category->name}} equipment for sale | rigmanagers.com</title>
        <meta name="description" content="Find new or used {{$filters['author']}} {{$category->name}} equipment for sale or rent at rigmanagers.com">
    @else
        <title>{{$category->meta_title}}</title>
        <meta name="description" content="Find new or used {{$category->name}} equipment for sale or rent at rigmanagers.com">
    @endif
@else
    @if (isset($filters['author']))
        <title>{{$filters['author']}} equipment for sale | rigmanagers.com</title>
        <meta name="description" content="Find new or used {{$filters['author']}} equipment for sale or rent at rigmanagers.com">
    @else
        <title>Oil&Gas equipment for sale | rigmanagers.com</title>
        <meta name="description" content="Find new or used drilling equipment for sale or rent at rigmanagers.com">
    @endif
@endif