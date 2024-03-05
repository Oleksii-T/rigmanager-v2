@extends('layouts.page')

@section('meta')
    <title>{{$post->meta_title}}</title>
    <meta name="description" content="{{$post->meta_description}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.catalog')" :href="route('search')" i="2" />
    @foreach ($post->category->parents(true) as $category)
        <x-bci :text="$category->name" :href="$category->getUrl()" :i="$loop->index + 3"/>
        @if ($loop->last)
            <x-bci :text="$post->title" :i="$loop->index + 4" islast="1"/>
        @endif
    @endforeach
@endsection

@section('style')
    <style>
        .prod-about table {
            margin-bottom: 24px;
        }
        .prod-about table td {
            border: 1px solid #505050;
        }
        .prod-about table td {
            padding: 0 5px 0 5px;
        }
    </style>
@endsection

@section('content')
    <span class="hidden" data-postid="{{$post->id}}" data-viewurl="{{route('posts.view', $post)}}" page-data></span>

    @if (!$post->is_active)
        <div class="outdated-post-alert">
            <p>{{__('ui.postIsHidden')}}</p>
        </div>
    @elseif ($post->is_trashed)
        <div class="outdated-post-alert">
            <p>Only you(author) can view this post, because it been trashed.</p>
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
                        <a href="#" class="prod-arrow prod-prev"></a>
                        <div class="prod-current"></div>
                        <div class="prod-divider"></div>
                        <div class="prod-all"></div>
                        <a href="#" class="prod-arrow prod-next"></a>
                    </div>
                @endif
            </div>
            @if ($post->images->isNotEmpty())
                <div class="prod-photo">
                    @foreach ($post->images as $i => $image)
                        <div class="prod-photo-slide">
                            <a href="{{$image->url}}" data-fancybox="postsgallery">
                                <img src="{{$image->url}}" alt="{{$post->title . ' - ' . trans('ui.seo-img-image-suffix'). ' ' . $i+1}}" title="{{$post->title . ' - ' . trans('ui.seo-img-image-suffix'). ' ' . $i+1}}">
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="prod-about">
                {{--
                @if ($post->failed_translation)
                    <div class="warning">
                        {{__('ui.posts.autoTranslationNotAvailable')}}
                    </div>
                @elseif ($currentLocale != $post->origin_lang && $post->auto_translate)
                    <div class="warning post-translated-text-toggle">
                        {{__('ui.originPostLang')}}
                        <a href="">{{$post->origin_lang}}</a>.
                    </div>
                    <div class="warning post-translated-text-toggle d-none">
                        {{__('ui.posts.showTranslatedAgain')}}
                        <a href="">{{$currentLocale}}</a>.
                    </div>
                    <h1 class="hidden">{{$post->translated('title', $post->origin_lang)}}</h1>
                    <p class="hidden">{{$post->translated('description', $post->origin_lang)}}</p>
                @endif
                --}}
                <h1>{{$post->title}}</h1>
                {{-- <a href="{{$post->scraped_url}}">{{$post->scraped_url}}</a> --}}
                {!!$post->description!!}
            </div>
        </div>
        <div class="prod-side">
            <x-post-author-block :post="$post" :hasChat="$hasChat" />
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
                    <div class="prod-info-text">{{$post->type->readable()}}</div>
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
                @if ($post->manufacture_date)
                    <div class="prod-info-item">
                        <div class="prod-info-name">@lang('ui.manufactureDate')</div>
                        <div class="prod-info-text">{{$post->manufacture_date}}</div>
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
                        <div class="prod-info-text">{{countries()[$post->country]}}</div>
                    </div>
                @endif
                @if ($post->cost_readable)
                    <div class="prod-info-item">
                        <div class="prod-info-name">{{__('ui.cost')}}</div>
                        <x-post-price :post="$post" class="prod-info-text" />
                    </div>
                @endif
                <div class="prod-info-item">
                    <div class="prod-info-name">{{__('ui.postCreated')}}</div>
                    <div class="prod-info-text">{{$post->updated_at->diffForHumans()}}</div>
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

@section('scripts')
    <script src="{{asset('js/posts.js')}}"></script>
@endsection
