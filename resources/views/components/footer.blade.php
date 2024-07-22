<footer class="footer">
    <div class="holder">
        <div class="footer-block">
            <div class="footer-copy">
                &copy; {{date("Y")}}
                <span>«Rigmanager»</span> - @lang('ui.introduction'). @lang('ui.footerCopyright')
            </div>
            <div class="footer-col">
                <ul class="footer-nav">
                    <li><a href="{{route('about')}}">@lang('ui.footerAbout')</a></li>
                    <li><a href="{{route('blog.index')}}">@lang('ui.footerBlog')</a></li>
                    <li><a href="{{route('search')}}">@lang('ui.catalog')</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <ul class="footer-nav">
                    {{-- <li><a href="{{route('plans.index')}}">@lang('ui.footerSubscription')</a></li> --}}
                    <li><a href="{{route('feedbacks.create')}}">@lang('ui.footerContact')</a></li>
                    <li><a href="{{route('faq')}}">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <ul class="footer-nav">
                    <li><a href="{{route('terms')}}">@lang('ui.footerTerms')</a></li>
                    <li><a href="{{route('privacy')}}">@lang('ui.footerPrivacy')</a></li>
                    <li><a href="{{route('site-map')}}">@lang('ui.footerSiteMap')</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <ul class="footer-nav">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        @if ($currentLocale == $localeCode)
                            <li>
                                <span>{{ $properties['native'] }}</span>
                            </li>
                        @else
                            <li>
                                <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
                <ul class="footer-nav">
                    <li><a href="https://www.linkedin.com/company/rigmanagers-com/about">LinkedIn</a></li>
                    <li><a href="https://www.facebook.com/rigmanagerscom">Facebook</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>