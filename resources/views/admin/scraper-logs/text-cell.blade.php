@if (strlen($text) > 200)
    <div>
        <span>{{substr($text, 0, 200)}}...</span>
        <a href="#" class="show-full-cell-content-btn" data-toggle="modal" data-target="#log-full-text-modal">more</a>
        <span class="full-cell-content d-none">
            <span style="white-space:pre;overflow:auto;text-wrap:wrap;">{{$text}}</span>
        </span>
    </div>
@else
    <span>{{$text}}</span>
@endif
