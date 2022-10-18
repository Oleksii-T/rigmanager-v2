@extends('layouts.admin.app')

@section('title', 'Feedback Preview')

@section('content_header')
    <x-admin.title
        text="Feedback Preview"
    />
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>User</label>
                        @if ($feedback->user)
                            <a href="{{route('admin.users.edit', $feedback->user)}}" class="form-control">{{$feedback->user->name}}</a>
                        @else
                            <input type="text" class="form-control" disabled>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" value="{{$feedback->email}}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="{{$feedback->name}}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" class="form-control" value="{{$feedback->subject}}" disabled>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Text</label>
                        <textarea rows="3" class="form-control" disabled>{{$feedback->text}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!$feedback->is_read)
        <a href="{{route('admin.feedbacks.read', $feedback)}}" type="submit" class="btn btn-success min-w-100">Mark as Read</a>
    @endif
    <a href="{{route('admin.feedbacks.index')}}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
@endsection
