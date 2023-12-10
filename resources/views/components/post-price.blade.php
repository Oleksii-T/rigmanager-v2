@isSub
    <div class="{{$class}}">{{$post->cost_readable}}</div>
@else
    <div
        class="{{$class}} blurred white c-pointer"
        data-subject="{{$post->id}}"
        @auth
            data-modal="sub-required"
            data-type="post-price-show-by-unsub"
            data-message="A paid Subscription required to see post prices."
        @else
            data-modal="auth-required"
            data-type="post-price-show-by-guest"
            data-message="Please login to see post prices."
        @endauth
    >
        $00.000,00
    </div>
@endisSub
