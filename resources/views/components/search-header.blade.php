@php
    $isService = $filters['group'] == \App\Enums\PostGroup::SERVICE;
    $title = trans($isService ? 'ui.seCatalog' : 'ui.eqCatalog')
@endphp

<h1>
    @if (isset($filters['author']))
        @isSub
            {{$filters['author_name']}}
        @else
            <span
                class="blurred c-pointer white b-13"
                data-subject="{{$filters['author']}}"
                @auth
                    data-modal="sub-required"
                    data-type="post-author-show-by-unsub"
                    data-message="A paid Subscription required to see post authors."
                @else
                    data-modal="auth-required"
                    data-type="post-author-show-by-guest"
                    data-message="Please login to see post authors."
                @endauth
            >
                Author Name
            </span>
        @endisSub
    @elseif (isset($filters['search']))
        "{{$filters['search']}}"
    @elseif (isset($category))
        {{$category->name}}
    @else
        {{$title}}
    @endif
    (<span class="orange searched-amount">_</span>)
</h1>
