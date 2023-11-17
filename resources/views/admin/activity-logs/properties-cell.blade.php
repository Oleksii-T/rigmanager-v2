@if (strlen($raw) > 40)
    <span class="alog-short-desc">{{substr($raw, 0, 40)}}...</span>
    <span class="badge badge-info" data-toggle="modal" data-target="#alog-full-props-{{$id}}">show</span>

    <div class="modal fade" id="alog-full-props-{{$id}}" style="display: none;">
        <div class="modal-dialog" style="width:90%;max-width:none;">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Properties of activity log #{{$id}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php dump($props) @endphp
                </div>
            </div>
        </div>
    </div>
@else
    <p>{{$raw}}</p>
@endif
