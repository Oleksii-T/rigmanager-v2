<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
    @if (!isset($islast) || !$islast)
    <a itemprop="item" href="{{$href}}">
    @endif
        <span itemprop="name">{{$text}}</span>
    @if (!isset($islast) || !$islast)
    </a>
    @endif
    <meta itemprop="position" content="{{$i}}" />
</li>
