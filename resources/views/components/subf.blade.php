<div class="sub-info-item">
    @if (isset($check))
        @if ($check)
            <img src="{{asset('icons/sub-check.svg')}}" alt="">
        @else
            <div class="sub-no"></div>
        @endif
    @endif
    <div class="sub-info-text">
        @lang($text)
        @if ($helpFaq??false)
            <a href="{{route('faq') . '#' .  $helpFaq}}" class="help-tooltip-icon" title="See FAQ">
                @svg('icons/goto.svg')
            </a>
        @endif
        @if ($helpText??false)
            <span class="help-tooltip-icon" title="{{trans($helpText)}}">
                @svg('icons/info.svg')
            </span>
        @endif
    </div>
</div>
