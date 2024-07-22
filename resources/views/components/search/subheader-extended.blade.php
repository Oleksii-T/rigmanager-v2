@if (isset($category) && $category->add_desc && strip_tags($category->add_desc))
    <div id="search-category-description">
        {!!$category->add_desc!!}
    </div>
@elseif(!isset($category) && ($filters['author_bio']??'') && strlen($filters['author_bio']) > 180)
    <div id="search-category-description">
        {{$filters['author_bio']}}
        <span class="link-w-icon">
            <a href="{{$filters['author_profile_link']}}">@lang('ui.profile')</a>
            <img src="{{asset('icons/link.svg')}}" alt="">
        </span>
    </div>
@elseif(!isset($category) && !isset($filters['author']))
    <div id="search-category-description">
        @lang('ui.catalogEndInfo')
    </div>
@endif