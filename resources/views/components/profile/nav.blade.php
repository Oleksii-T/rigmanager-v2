<aside class="side">
    <a href="#side-block" data-fancybox class="side-mob">@lang('ui.cabinet')</a>
    <div class="side-block" id="side-block">
        <div class="side-title">@lang('ui.cabinet')</div>
        <ul class="side-list">
            <li><a href="{{route('profile.index')}}" class="{{$active=="profile" ? 'active' : ''}}">@lang('ui.profileInfo')</a></li>
            <li><a href="{{route('profile.posts')}}" class="{{$active=="posts" ? 'active' : ''}}">@lang('ui.myPosts')</a></li>
            <li><a href="{{route('profile.favorites')}}" class="{{$active=="fav" ? 'active' : ''}}">@lang('ui.favourites')</a></li>
            <li><a href="" class="{{$active=="mailer" ? 'active' : ''}}">@lang('ui.mailer')</a></li>
            <li><a href="" class="{{$active=="subscription" ? 'active' : ''}}">@lang('ui.mySubscription')</a></li>
            @if ($currentUser->isAdmin())
                <li><a href="{{route('admin.index')}}">Admin Panel</a></li>
            @endif
            <li><a href="{{route('logout')}}" >@lang('ui.signOut')</a></li>
        </ul>
    </div>
</aside>
