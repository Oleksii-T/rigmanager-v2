@if (strlen($d) > 40)
    <span class="alog-short-desc">{{substr($d, 0, 40)}}...</span>
    <span class="badge badge-info" data-toggle="modal" data-target="#alog-full-desc-{{$id}}">show</span>

    <div class="modal fade" id="alog-full-desc-{{$id}}" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Description of activity log #{{$id}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="white-space: pre-line">
                        {!!$d!!}
                    </p>
                </div>
            </div>
        </div>
    </div>
@else
    <p>{{$d}}</p>
@endif
