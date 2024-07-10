@if (isset($category) && $category->add_desc_short && strip_tags($category->add_desc_short))
    <div class="search-category-short-description">
        {!!$category->add_desc_short!!}
        @if ($category->add_desc)
            <a href="#search-category-description">@lang('ui.readMore')</a>
        @endif
    </div>
@elseif(!isset($category) && ($filters['author_bio']??''))
    <div class="search-category-short-description">
        @if (strlen($filters['author_bio']) > 180)
            <p class="wrap-text">{{substr($filters['author_bio'], 0 , 180)}}...</p>
            <a href="#search-category-description">@lang('ui.readMore')</a>
        @else
            <p class="wrap-text">{{$filters['author_bio']}}
        @endif
    </div>
@elseif(!isset($category) && !isset($filters['author']))
    <div class="search-category-short-description">
        @lang('ui.catalogHeadline')
        <a href="#search-category-description">@lang('ui.readMore')</a>
    </div>
@endif
