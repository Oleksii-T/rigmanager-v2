@if (isset($category) && $category->add_desc && strip_tags($category->add_desc))
    <div id="search-category-description">
        {!!$category->add_desc!!}
    </div>
@elseif(!isset($category) && ($filters['author_bio']??'') && strlen($filters['author_bio']) > 180)
    <div id="search-category-description">
        <p class="wrap-text">{{$filters['author_bio']}}
    </div>
@elseif(!isset($category) && !isset($filters['author']))
    <div id="search-category-description">
        @lang('ui.catalogEndInfo')
    </div>
@endif