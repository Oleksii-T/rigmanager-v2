@foreach ($filters as $key => $value)
    @if (!$value)
        @continue
    @endif
    {{$key}}: {{is_array($value) ? json_encode($value) : $value}}
    @if (!$loop->last)
        <br>
    @endif
@endforeach
