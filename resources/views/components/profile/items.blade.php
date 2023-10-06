
@if ($posts->isNotEmpty())
    <div class="catalog catalog-my">
        @foreach ($posts as $post)
            <div class="catalog-item" data-id="{{$post->id}}">
                <!--post-image-->
                <a href="{{route('posts.show', $post)}}" class="catalog-img">
                    <img src="{{$post->thumbnail()->url ?? asset('icons/no-image.svg')}}" alt="{{$post->title}}" title="{{$post->title}}">
                </a>
                <!--all post preview but image-->
                <div class="catalog-content">
                    <!--title-->
                    <div class="catalog-name"><a href="{{route('posts.show', $post)}}">{{$post->title}}</a></div>
                    <!--under title line. Lables: type, view, region, date-->
                    <div class="catalog-line">
                        <!--type-->
                        <a href="{{route('posts.show', $post)}}" class="catalog-tag">{{\App\Models\Post::typeReadable($post->type)}}</a>
                        <!--country-->
                        <div class="catalog-lable catalog-region">{{$post->country_readable}}</div>
                        <!--views-->
                        <div class="catalog-lable">
                            {{__('ui.views') . ': ' . $post->views->count()}}
                            {{-- <a href="{{route('posts.views', $post)}}" class="show-post-views">{{__('ui.views') . ': ' . $post->views->count()}}</a> --}}
                        </div>
                        <!--date-->
                        <div class="catalog-date">{{$post->created_at->diffForHumans()}}</div>
                    </div>
                    <!--description-->
                    <div class="catalog-text">{{$post->description}}</div>
                    <!--under description line. Lables: cost, urgent, import-->
                    <div class="catalog-line-bottom">
                        <!--price-->
                        <div class="catalog-price">{{$post->cost_readable}}</div>
                        <!--urgent+import-->
                        <div>
                            <!--urgent-->
                            @if ($post->is_urgent)
                                <div class="catalog-lable orange">{{__('ui.urgent')}}</div>
                            @endif
                        </div>
                    </div>
                    <!--bar. Buttons: edit, hide, delete-->
                    <div class="bar">
                        <div class="check-item">
                            <input type="checkbox" class="check-input" id="check-{{$post->id}}">
                            <label for="check-{{$post->id}}" class="check-label"></label>
                        </div>
                        <div class="bar-icons">
                            <a href="{{route('posts.edit', $post) }}" class="bar-edit">
                                <svg viewBox="0 0 401 398.99" xmlns="http://www.w3.org/2000/svg">
                                    <path transform="translate(0)" d="M370.11,250.39a10,10,0,0,0-10,10v88.68a30,30,0,0,1-30,30H49.94a30,30,0,0,1-30-30V88.8a30,30,0,0,1,30-30h88.67a10,10,0,1,0,0-20H49.94A50,50,0,0,0,0,88.8V349.05A50,50,0,0,0,49.94,399H330.16a50,50,0,0,0,49.93-49.94V260.37a10,10,0,0,0-10-10"/>
                                    <path transform="translate(0)" d="M376.14,13.16a45,45,0,0,0-63.56,0L134.41,191.34a10,10,0,0,0-2.57,4.39l-23.43,84.59a10,10,0,0,0,12.29,12.3l84.59-23.44a10,10,0,0,0,4.4-2.56L387.86,88.44a45,45,0,0,0,0-63.56Zm-220,184.67L302,52l47,47L203.19,244.86Zm-9.4,18.85,37.58,37.58-52,14.39Zm227-142.36-10.6,10.59-47-47,10.6-10.59a25,25,0,0,1,35.3,0L373.74,39a25,25,0,0,1,0,35.31"/>
                                </svg>
                            </a>
                            <a href="{{route('posts.toggle', $post)}}" class="bar-view {{!$post->is_active ? 'active' : ''}}">
                                <svg viewBox="0 0 512 383.98" xmlns="http://www.w3.org/2000/svg">
                                    <path transform="translate(0)" d="m316.33 131.65a10.67 10.67 0 1 0-15.08 15.09 64 64 0 1 1-90.5 90.49 10.67 10.67 0 1 0-15.08 15.09 85.32 85.32 0 1 0 120.66-120.67"/>
                                    <path transform="translate(0)" d="M270.87,108.12A84.49,84.49,0,0,0,170.67,192a83.85,83.85,0,0,0,1.49,14.87,10.68,10.68,0,0,0,10.48,8.81,9.23,9.23,0,0,0,1.87-.17,10.67,10.67,0,0,0,8.64-12.35A62,62,0,0,1,192,192a63.24,63.24,0,0,1,75.16-62.87,10.66,10.66,0,0,0,3.71-21"/>
                                    <path transform="translate(0)" d="M509.46,185.09c-2.41-2.86-60.11-70.2-139.71-111.44a10.67,10.67,0,1,0-9.79,19c61.31,31.75,110.29,81.28,127,99.38-25.43,27.54-125.51,128-231,128-35.8,0-71.87-8.65-107.26-25.71a10.66,10.66,0,0,0-9.26,19.2C177.77,332,217,341.32,256,341.32c131.44,0,248.56-136.62,253.49-142.44a10.68,10.68,0,0,0,0-13.79"/>
                                    <path transform="translate(0)" d="M326,54.94c-24.28-8.17-47.83-12.29-70-12.29C124.57,42.65,7.45,179.27,2.52,185.09a10.68,10.68,0,0,0-.6,13c1.47,2.11,36.74,52.18,97.86,92.77a10.45,10.45,0,0,0,5.89,1.8,10.68,10.68,0,0,0,5.88-19.57c-44.88-29.84-75.6-65.87-87.1-80.53C49,165.89,149.74,64,256,64c19.86,0,41.13,3.76,63.19,11.16a10.51,10.51,0,0,0,13.5-6.7A10.64,10.64,0,0,0,326,54.94"/>
                                    <path transform="translate(0)" d="M444.87,3.12a10.68,10.68,0,0,0-15.09,0L67.12,365.79A10.66,10.66,0,0,0,82.2,380.87L444.87,18.2a10.67,10.67,0,0,0,0-15.08"/>
                                </svg>
                            </a>
                            <a href="{{route('posts.destroy', $post)}}" class="bar-delete">
                                @svg('icons/trash.svg')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="pagination-field">
        {{ $posts->appends(request()->input())->links() }}
    </div>
@else
    <p>@lang('ui.noMyPosts')</p>
@endif
