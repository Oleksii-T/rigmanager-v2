<div class="post-view-title">
    @lang('ui.totalUniqViews'): {{$views->count()}}
</div>
<div class="post-view-wrapper">
    @foreach ($views as $view)
        <div class="view-item">
            <p>{{$view->user->name ?? __('ui.guest')}}:</p>
            <p>@lang('ui.views'): {{$view->count}}</p>
            <p>@lang('ui.lastView'):  {{$view->updated_at->format('Y M, d')}}</p>
        </div>
    @endforeach
</div>
