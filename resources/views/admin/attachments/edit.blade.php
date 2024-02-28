@extends('layouts.admin.app')

@section('title', 'Create Attachment')

@section('content_header')
    <x-admin.title
        text="Edit Attachment #{{$attachment->id}}"
    />
@stop

@section('content')
    <form action="{{ route('admin.attachments.update', $attachment) }}" method="POST" class="general-ajax-submit">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="original_name" class="form-control" value="{{$attachment->original_name}}">
                            <span class="input-error" data-input="original_name"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" class="form-control" value="{{$attachment->type}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Resource</label>
                            <input type="text" class="form-control" value="{{$attachment->attachmentable_type}} #{{$attachment->attachmentable_id}}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Size</label>
                            <input type="text" class="form-control" value="{{$attachment->getSize()}} ({{$attachment->size}} b)" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Alt</label>
                            <input type="text" name="alt" class="form-control" value="{{$attachment->alt}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>URL</label>
                            <a href="{{$attachment->url}}" target="_blank" class="form-control">{{$attachment->url}}</a>
                            @if ($attachment->type == 'image')
                                <img style="max-width:100%;max-height:300px" src="{{$attachment->url}}" alt="">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Group</label>
                            <input type="text" name="group" class="form-control" value="{{$attachment->group}}">
                            <span class="input-error" data-input="group"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($resized)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Resized versions:</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($resized as $i => $r)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{$i}}: URL</label>
                                    <a href="{{$r['url']}}" target="_blank" class="form-control">{{$r['url']}}</a>
                                    <img style="max-width:100%;max-height:300px" src="{{$r['url']}}" alt="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{$i}}: PATH</label>
                                    <p class="form-control">{{$r['path']}}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.attachments.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
