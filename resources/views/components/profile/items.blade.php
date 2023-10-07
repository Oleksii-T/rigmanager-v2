
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
                            <a href="{{route('posts.edit', $post) }}" class="bar-edit" title="Edit">
                                <svg viewBox="0 0 401 398.99" xmlns="http://www.w3.org/2000/svg">
                                    <path transform="translate(0)" d="M370.11,250.39a10,10,0,0,0-10,10v88.68a30,30,0,0,1-30,30H49.94a30,30,0,0,1-30-30V88.8a30,30,0,0,1,30-30h88.67a10,10,0,1,0,0-20H49.94A50,50,0,0,0,0,88.8V349.05A50,50,0,0,0,49.94,399H330.16a50,50,0,0,0,49.93-49.94V260.37a10,10,0,0,0-10-10"/>
                                    <path transform="translate(0)" d="M376.14,13.16a45,45,0,0,0-63.56,0L134.41,191.34a10,10,0,0,0-2.57,4.39l-23.43,84.59a10,10,0,0,0,12.29,12.3l84.59-23.44a10,10,0,0,0,4.4-2.56L387.86,88.44a45,45,0,0,0,0-63.56Zm-220,184.67L302,52l47,47L203.19,244.86Zm-9.4,18.85,37.58,37.58-52,14.39Zm227-142.36-10.6,10.59-47-47,10.6-10.59a25,25,0,0,1,35.3,0L373.74,39a25,25,0,0,1,0,35.31"/>
                                </svg>
                            </a>
                            @if ($post->is_trashed)
                                <a href="{{route('posts.recover', $post)}}" class="bar-recover" title="Recover From Trash">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 14 14" role="img" focusable="false" aria-hidden="true">
                                        <path d="m 3.9587397,12.277725 c -0.42211,-0.1956 -0.65803,-0.532 -0.72456,-1.033 -0.0732,-0.5514 -0.44436,-3.9027999 -0.43353,-3.9147999 0.006,-0.01 0.0844,0.01 0.17447,0.032 0.18456,0.05 0.42832,0.023 0.55694,-0.061 0.068,-0.044 0.0894,-0.046 0.1017,-0.01 0.009,0.027 0.11156,0.9206 0.22891,1.9862 0.11735,1.0655999 0.22208,1.9664999 0.23272,2.0021999 0.0106,0.036 0.0711,0.1083 0.13421,0.1614 l 0.11485,0.097 2.1986,0 c 1.62368,0 2.22464,-0.012 2.2982,-0.045 0.0548,-0.025 0.12307,-0.091 0.15175,-0.1462 0.0315,-0.061 0.15952,-1.0558 0.32296,-2.5092999 0.14894,-1.3247 0.30879,-2.7445 0.35523,-3.1551 l 0.0844,-0.7467 -1.36942,-0.5803 c -0.75318,-0.3192 -1.90602,-0.8075 -2.56186,-1.0852 -0.65584,-0.2777 -1.22303,-0.5326 -1.26041,-0.5664 -0.19254,-0.1743 -0.24225,-0.5353 -0.10686,-0.7762 0.16289,-0.2898 0.53087,-0.3822 0.89459,-0.2245 0.10097,0.044 0.58531,0.2503 1.07631,0.4589 0.49099,0.2086 0.91164,0.3873 0.93477,0.3972 0.0231,0.01 0.0874,-0.075 0.14292,-0.1882 0.13455,-0.275 0.40789,-0.525 0.69067,-0.6317 0.35908,-0.1355 0.64306,-0.1006 1.23269,0.1514 0.5658,0.2418 0.7028093,0.3227 0.8713093,0.5146 0.28506,0.3247 0.37291,0.7746 0.23225,1.1895 -0.0453,0.1335 -0.0685,0.2554 -0.0516,0.2708 0.0169,0.015 0.52645,0.2371 1.13236,0.4927 0.60591,0.2557 1.14232,0.5013 1.19202,0.546 0.20589,0.1849 0.25867,0.5645 0.10907,0.7843 -0.14599,0.2145 -0.27358,0.2847 -0.51562,0.2835 -0.18612,-9e-4 -0.32618,-0.048 -1.02289,-0.347 -0.44399,-0.1903 -0.80725,-0.3425 -0.80725,-0.3382 0,0 -0.0941,0.8443 -0.20922,1.8666 -0.11507,1.0223 -0.26959,2.3972 -0.34339,3.0552999 -0.073799,0.6581 -0.1596193,1.2735 -0.1907193,1.3676 -0.0704,0.2128 -0.23244,0.4301 -0.42802,0.574 -0.31069,0.2284 -0.25076,0.2237 -2.85065,0.2233 l -2.37896,-3e-4 -0.20894,-0.097 z m 5.78177,-9.0863999 c 0,-0.1942 -0.14548,-0.3112 -0.64273,-0.5167 -0.47163,-0.1949 -0.49985,-0.2002 -0.65322,-0.1208 -0.11486,0.059 -0.25934,0.313 -0.20633,0.3622 0.0271,0.025 1.06768,0.4737 1.3194,0.5687 0.0637,0.024 0.0909,0.011 0.13159,-0.065 0.0282,-0.053 0.0513,-0.1552 0.0513,-0.2284 z m -4.78159,7.5330999 c -0.0663,-0.029 -0.14167,-0.092 -0.16757,-0.1404 -0.0458,-0.085 -0.14383,-0.8205999 -0.14717,-1.1033999 l -0.002,-0.1406 0.16145,0.073 c 0.19084,0.087 0.29842,0.09 0.50189,0.018 0.0828,-0.029 0.15533,-0.049 0.16123,-0.043 0.0249,0.025 0.11652,0.9907999 0.0992,1.0459999 -0.0965,0.3079 -0.32042,0.4149 -0.60738,0.29 z m 1.36953,-0.017 c -0.1763,-0.1075 -0.2044,-0.2415 -0.2044,-0.9745999 l 0,-0.6558 0.19944,-0.1226 c 0.10969,-0.067 0.29643,-0.197 0.41498,-0.2881 0.11855,-0.091 0.22539,-0.1657 0.23743,-0.1657 0.012,0 0.0219,0.4317 0.0218,0.9592 -4e-5,0.8372999 -0.008,0.9760999 -0.0665,1.0916999 -0.11371,0.2267 -0.37558,0.2944 -0.60279,0.1559 z m 1.44134,0.011 c -0.0765,-0.038 -0.14062,-0.1104 -0.17518,-0.1986 -0.0498,-0.1271 -0.0353,-0.3209 0.16771,-2.2471999 0.2451,-2.3255 0.22896,-2.2513 0.51149,-2.3507 0.20101,-0.071 0.40555,0.01 0.49388,0.1954 0.058,0.1217 0.05,0.2364 -0.15174,2.1769 -0.11713,1.1267 -0.23091,2.1134999 -0.25285,2.1927999 -0.0696,0.2517 -0.33904,0.3568 -0.59331,0.2314 z m -2.79487,-1.8402999 c -0.0366,-0.022 -0.0665,-0.079 -0.0665,-0.1277 0,-0.069 0.0561,-0.1207 0.254,-0.2356 0.13969,-0.081 0.35224,-0.238 0.47232,-0.3486 0.98933,-0.9117 0.13301,-2.0157 -1.78998,-2.3078 -0.19849,-0.03 -0.37371,-0.055 -0.38938,-0.055 -0.0157,-10e-5 -0.0285,0.1964 -0.0285,0.4367 0,0.3862 -0.009,0.4456 -0.076,0.5128 -0.0931,0.093 -0.21838,0.097 -0.34293,0.011 -0.24702,-0.1707 -1.95973,-1.7043 -1.99116,-1.7829 -0.0224,-0.056 -0.0224,-0.1244 0,-0.1828 0.0197,-0.051 0.48708,-0.4872 1.03861,-0.9687 1.06851,-0.9328 1.1264,-0.9685 1.29548,-0.7994 0.0667,0.067 0.076,0.1266 0.076,0.4899 l 0,0.4139 0.33201,0.047 c 0.80296,0.1139 1.66768,0.4 2.26534,0.7494 0.42462,0.2482 0.95481,0.7765 1.11706,1.1131 0.24465,0.5076 0.24994,0.9941 0.0162,1.4931 -0.2442,0.5215 -0.718,0.9455 -1.47037,1.316 -0.50585,0.249 -0.61481,0.2835 -0.71227,0.2254 z"/>
                                    </svg>
                                </a>
                                <a href="{{route('posts.destroy', $post)}}" class="bar-delete" title="Permanently Delete">
                                    @svg('icons/trash.svg')
                                </a>
                            @else
                                <a href="{{route('posts.toggle', $post)}}" class="bar-view {{!$post->is_active ? 'active' : ''}}" title="{{$post->is_active ? 'Deactivate' : 'Activate'}}">
                                    <svg viewBox="0 0 512 383.98" xmlns="http://www.w3.org/2000/svg">
                                        <path transform="translate(0)" d="m316.33 131.65a10.67 10.67 0 1 0-15.08 15.09 64 64 0 1 1-90.5 90.49 10.67 10.67 0 1 0-15.08 15.09 85.32 85.32 0 1 0 120.66-120.67"/>
                                        <path transform="translate(0)" d="M270.87,108.12A84.49,84.49,0,0,0,170.67,192a83.85,83.85,0,0,0,1.49,14.87,10.68,10.68,0,0,0,10.48,8.81,9.23,9.23,0,0,0,1.87-.17,10.67,10.67,0,0,0,8.64-12.35A62,62,0,0,1,192,192a63.24,63.24,0,0,1,75.16-62.87,10.66,10.66,0,0,0,3.71-21"/>
                                        <path transform="translate(0)" d="M509.46,185.09c-2.41-2.86-60.11-70.2-139.71-111.44a10.67,10.67,0,1,0-9.79,19c61.31,31.75,110.29,81.28,127,99.38-25.43,27.54-125.51,128-231,128-35.8,0-71.87-8.65-107.26-25.71a10.66,10.66,0,0,0-9.26,19.2C177.77,332,217,341.32,256,341.32c131.44,0,248.56-136.62,253.49-142.44a10.68,10.68,0,0,0,0-13.79"/>
                                        <path transform="translate(0)" d="M326,54.94c-24.28-8.17-47.83-12.29-70-12.29C124.57,42.65,7.45,179.27,2.52,185.09a10.68,10.68,0,0,0-.6,13c1.47,2.11,36.74,52.18,97.86,92.77a10.45,10.45,0,0,0,5.89,1.8,10.68,10.68,0,0,0,5.88-19.57c-44.88-29.84-75.6-65.87-87.1-80.53C49,165.89,149.74,64,256,64c19.86,0,41.13,3.76,63.19,11.16a10.51,10.51,0,0,0,13.5-6.7A10.64,10.64,0,0,0,326,54.94"/>
                                        <path transform="translate(0)" d="M444.87,3.12a10.68,10.68,0,0,0-15.09,0L67.12,365.79A10.66,10.66,0,0,0,82.2,380.87L444.87,18.2a10.67,10.67,0,0,0,0-15.08"/>
                                    </svg>
                                </a>
                                <a href="{{route('posts.trash', $post)}}" class="bar-delete only-trash" title="Move To Trash">
                                    @svg('icons/trash.svg')
                                </a>
                            @endif
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
