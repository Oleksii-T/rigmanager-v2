@extends('layouts.page')

@section('meta')
	<title>{{$post->title . ' - ' . $post->category->name . ' ' . __('meta.title.post.show')}}</title>
	<meta name="description" content="{{$post->cost_readable . ': ' . (strlen($post->description)>90 ? substr($post->description, 0, 90) . '...' : $post->description)}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{route('search')}}"><span itemprop="name">{{__('ui.catalog')}}</span></a>
        <meta itemprop="position" content="2" />
    </li>
    @foreach ($post->category->parents(true) as $category)
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemprop="item" href="{{route('search.category', $category)}}"><span itemprop="name">{{$category->name}}</span></a>
            <meta itemprop="position" content="{{$loop->index+3}}" />
        </li>
        @if ($loop->last)
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name">{{$post->title}}</span>
                <meta itemprop="position" content="{{$loop->index+4}}" />
            </li>
        @endif
    @endforeach
@endsection

@section('content')
    <span class="hidden" data-postid="{{$post->id}}" page-data></span>

    @if (!$post->is_active)
        <div class="outdated-post-alert">
            <p>{{__('ui.postIsHidden')}}</p>
        </div>
    @endif
    <div class="prod">
        <div class="prod-content">
            <div class="prod-top">
                <a href="{{route('posts.add-to-fav', $post)}}" class="catalog-fav add-to-fav {{$currentUser?->favorites->contains($post) ? 'active' : ''}}">
                    <svg viewBox="0 0 464 424" xmlns="http://www.w3.org/2000/svg">
                        <path class="cls-1" d="M340,0A123.88,123.88,0,0,0,232,63.2,123.88,123.88,0,0,0,124,0C55.52,0,0,63.52,0,132,0,304,232,424,232,424S464,304,464,132C464,63.52,408.48,0,340,0Z"/>
                    </svg>
                    {{__('ui.addToFav')}}
                </a>
                @if ($post->images->isNotEmpty())
                    <div class="prod-controls">
                        <a href="" class="prod-arrow prod-prev"></a>
                        <div class="prod-current"></div>
                        <div class="prod-divider"></div>
                        <div class="prod-all"></div>
                        <a href="" class="prod-arrow prod-next"></a>
                    </div>
                @endif
            </div>
            @if ( $post->images->isNotEmpty() )
                <div class="prod-photo">
                    @foreach ($post->images as $i => $image)
                        <div class="prod-photo-slide">
                            <a href="{{$image->url}}" data-fancybox="prod">
                                <img src="{{$image->url}}" alt="{{$post->title . ' - ' . trans('ui.seo-img-image-suffix'). ' ' . $i+1}}" title="{{$post->title . ' - ' . trans('ui.seo-img-image-suffix'). ' ' . $i+1}}">
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="prod-about">
                @if (LaravelLocalization::getCurrentLocale() != $post->origin_lang)
                    <div class="warning">{{__('ui.originPostLang')}} <a class="show-origin-text" href="">{{$post->origin_lang_readable}}</a>.</div>
                    <h1 class="hidden">{{$post->translated('title', $post->origin_lang)}}</h1>
                    <p class="hidden">{{$post->translated('description', $post->origin_lang)}}</p>
                @endif
                <h1>{{$post->title}}</h1>
                <p>{{$post->description}}</p>
            </div>
        </div>
        <div class="prod-side">
            <div class="prod-author">
                <div class="prod-author-info">
                    @if ($post->user->avatar)
                        <img class="prod-author-ava" src="{{$post->user->avatar->url}}" alt="{{$post->user->avatar->alt}}">
                    @else
                        <img class="prod-author-ava" src="{{asset('icons/emptyAva.svg')}}" alt="">
                    @endif
                    <div class="prod-author-about">
                        <div class="prod-author-name">{{$post->user->name}}</div>
                        <a href="{{route('search', ['author'=>$post->user->slug])}}" class="prod-author-link">{{__('ui.allAuthorPosts')}}</a>
                        @auth
                            <br>
                            @if ($post->user_id != $currentUser->id)
                                @if (auth()->user()->mailers && auth()->user()->mailers->pluck('author')->contains($post->user_id))
                                    <a href="" class="prod-author-link add-to-mailer">{{__('ui.mailerAuthorAlreadyAdded')}}</a>
                                @else
                                    <a href="" class="prod-author-link add-to-mailer">{{__('ui.mailerAddAuthor')}}</a>
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>
                <a href="#" class="button button-light show-contacts">{{__('ui.showContacts')}}</a>
                <button href="#popup-contacts" class="hidden open-contacts" data-fancybox></button>
                @if ($currentUser && $post->user_id==$currentUser->id)
                    <br>
                    <a href="{{route('posts.edit', $post)}}" class="button button-light">{{__('ui.edit')}}</a>
                @endif
            </div>
            @if ($post->documents->isNotEmpty())
                <div class="prod-info">
                    <div class="prod-info-title">{{__('ui.attDoc')}}</div>
                    @foreach ($post->documents as $doc)
                        <div class="prod-info-item prod-doc">
                            <div class="prod-info-text"><span class="orange">{{$doc->original_name}}</span></div>
                            <a href="{{route('attachments.download', $doc)}}" class="button button-blue">{{__('ui.download')}}</a>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="prod-info">
                <div class="prod-info-title">{{__('ui.info')}}</div>
                @if ($post->is_urgent)
                    <div class="prod-info-item">
                        <div class="prod-info-text"><span class="orange">{{__('ui.urgent')}}</span></div>
                    </div>
                @endif
                @if ($post->is_import)
                    <div class="prod-info-item">
                        <div class="prod-info-text"><span class="orange">{{__('ui.import')}}</span></div>
                    </div>
                @endif
                <div class="prod-info-item">
                    <div class="prod-info-name">{{__('ui.postType')}}</div>
                    <div class="prod-info-text">{{\App\Models\Post::typeReadable($post->type)}}</div>
                </div>
                @if ($post->amount)
                    <div class="prod-info-item">
                        <div class="prod-info-name">{{__('ui.amount')}}</div>
                        <div class="prod-info-text">{{$post->amount}}</div>
                    </div>
                @endif
                @if ($post->condition)
                    <div class="prod-info-item">
                        <div class="prod-info-name">{{__('ui.condition')}}</div>
                        <div class="prod-info-text">{{\App\Models\Post::conditionReadable($post->condition)}}</div>
                    </div>
                @endif
                @if ($post->manufacturer)
                    <div class="prod-info-item">
                        <div class="prod-info-name">{{__('ui.manufacturer')}}</div>
                        <div class="prod-info-text">{{$post->manufacturer}}</div>
                    </div>
                @endif
                @if ($post->manufactured_date)
                    <div class="prod-info-item">
                        <div class="prod-info-name">{{__('ui.manufacturedDate')}}</div>
                        <div class="prod-info-text">{{$post->manufactured_date}}</div>
                    </div>
                @endif
                @if ($post->part_number)
                    <div class="prod-info-item">
                        <div class="prod-info-name">{{__('ui.partNum')}}</div>
                        <div class="prod-info-text">{{$post->part_number}}</div>
                    </div>
                @endif
                @if ($post->country)
                    <div class="prod-info-item">
                        <div class="prod-info-name">{{__('ui.location')}}</div>
                        <div class="prod-info-text">{{$post->country}}</div>
                    </div>
                @endif
                @if ($post->cost)
                    <div class="prod-info-item">
                        <div class="prod-info-name">{{__('ui.cost')}}</div>
                        <div class="prod-info-text">{{$post->cost_readable}}</div>
                    </div>
                @endif
                <div class="prod-info-item">
                    <div class="prod-info-name">{{__('ui.postCreated')}}</div>
                    <div class="prod-info-text">{{$post->created_at->diffForHumans()}}</div>
                </div>
            </div>
        </div>
    </div>
    @if ($authorPosts->isNotEmpty())
        <div class="horizontal-posts author-posts">
            <div class="horizontal-posts-top">
                <h2>{{__('ui.otherAuthorPosts')}}</h2>
                <div class="prod-controls author-posts-controls">
                    <a href="" class="prod-arrow prod-prev"></a>
                    <div class="prod-current"></div>
                    <div class="prod-divider"></div>
                    <div class="prod-all"></div>
                    <a href="" class="prod-arrow prod-next"></a>
                </div>
            </div>
            <div class="horizontal-posts-slider author-posts-slider">
                <x-home-items :posts="$authorPosts"/>
                <div class="ad-col ad-col-more">
                    <a href="{{route('search', ['author'=>$post->user->url_name])}}" class="ad-more">{{__('ui.allAuthorPosts')}}</a>
                </div>
            </div>
        </div>
    @endif
    @if ($simPosts->isNotEmpty())
        <div class="horizontal-posts similar-posts">
            <div class="horizontal-posts-top">
                <h2>{{__('ui.similarPosts')}}</h2>
                <div class="prod-controls similar-posts-controls">
                    <a href="" class="prod-arrow prod-prev"></a>
                    <div class="prod-current"></div>
                    <div class="prod-divider"></div>
                    <div class="prod-all"></div>
                    <a href="" class="prod-arrow prod-next"></a>
                </div>
            </div>
            <div class="horizontal-posts-slider similar-posts-slider">
                <x-home-items :posts="$simPosts"/>
            </div>
        </div>
    @endif
@endsection

@section('modals')
    <div id="popup-contacts" class="popup">
        <div class="popup-title">{{__('ui.contactInfo')}}</div>
        <div class="popup-prod-info">
            <div class="prod-info-item contact-email">
                <div class="prod-info-name">{{__('ui.email')}}:</div>
                <div class="prod-info-text"></div>
            </div>
            <div class="prod-info-item contact-phone">
                <div class="prod-info-name">{{__('ui.phone')}}:</div>
                <div class="prod-info-text"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/posts.js')}}"></script>
@endsection
