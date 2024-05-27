<div>
    <a href="#" class="show-full-cell-content-btn" data-toggle="modal" data-target="#log-full-text-modal">
        {{substr(json_encode($data), 0, 200)}}...
    </a>
    <div class="full-cell-content d-none">
        @foreach ($data as $field => $value)
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{$field}}</h3>
                </div>
                <div class="card-body">
                    @if (is_array($value))
                        <ul>
                            @foreach ($value as $valueItem)
                                <li>{{$valueItem}}</li>
                            @endforeach
                        </ul>
                    @else
                        {{$value}}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
