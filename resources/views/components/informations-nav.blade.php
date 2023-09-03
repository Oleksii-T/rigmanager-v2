<aside class="side">
    <a href="#side-block" data-fancybox="mobilemenu" class="side-mob">{{__('ui.info')}}</a>
    <div class="side-block" id="side-block">
        <div class="side-title">{{__('ui.info')}}</div>
        <ul class="side-list">
            <li><a href="{{route('about')}}" class="{{$active=="about" ? 'active' : ''}}">{{__('ui.footerAbout')}}</a></li>
            <li><a href="{{route('blog.index')}}" class="{{$active=="blog" ? 'active' : ''}}">{{__('ui.footerBlog')}}</a></li>
            <li><a href="{{route('terms')}}" {{$active=="terms" ? 'class=active' : ''}}>{{__('ui.footerTerms')}}</a></li>
            <li><a href="{{route('privacy')}}" {{$active=="pp" ? 'class=active' : ''}}>{{__('ui.footerPrivacy')}}</a></li>
            <li><a href="{{route('site-map')}}" {{$active=="sitemap" ? 'class=active' : ''}}>{{__('ui.footerSiteMap')}}</a></li>
            <li><a href="{{route('plans.index')}}" {{$active=="plans" ? 'class=active' : ''}}>{{__('ui.footerSubscription')}}</a></li>
            <li><a href="{{route('feedbacks.create')}}" {{$active=="contact" ? 'class=active' : ''}}>{{__('ui.footerContact')}}</a></li>
            <li><a href="{{route('import-rules')}}" {{$active=="xlsx-info" ? 'class=active' : ''}}>{{__('postImportRules.title')}}</a></li>
            <li><a href="{{route('faq')}}" {{$active=="faq" ? 'class=active' : ''}}>FAQ</a></li>
        </ul>
    </div>
</aside>
