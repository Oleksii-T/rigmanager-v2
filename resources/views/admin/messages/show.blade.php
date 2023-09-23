@extends('layouts.admin.app')

@section('title', 'Chat messages')

@section('content_header')
    <x-admin.title
        text="Chat messages"
    />
@stop

@section('content')
    <div class="pb-3 general-ajax-submit">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="mr-2">General Info</span>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User 1</label>
                            <a href="{{route('admin.users.show', $u1)}}" class="form-control">{{$u1->name}}</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User 2</label>
                            <a href="{{route('admin.users.show', $u2)}}" class="form-control">{{$u2->name}}</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total Messages</label>
                            <input type="text" class="form-control" value="{{$total}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last At</label>
                            <input type="text" class="form-control" value="{{$lastAt}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="mr-2">Messages</span>
                </h3>
            </div>
            <div class="card-body">
                <table id="messages-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="ids-column">Id</th>
                            <th>User</th>
                            <th>Text</th>
                            <th>Is Read</th>
                            <th>Send At</th>
                            <th class="actions-column-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/messages.js')}}"></script>
@endpush
