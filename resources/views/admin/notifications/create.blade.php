@extends('layouts.admin.app')

@section('title', 'Notification Create')

@section('content_header')
    <x-admin.title
        text="Notification create"
    />
@stop

@section('content')
    <form action="{{route('admin.notifications.store')}}" method="POST" class="pb-3 general-ajax-submit">
        @csrf
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
                            <label>User</label>
                            <div class="select-block">
                                <select class="form-control select2" name="user_id" style="width: 100%">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span data-input="user_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Text</label>
                            <x-admin.multi-lang-input name="text" />
                            <span data-input="text" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type</label>
                            <div class="select-block">
                                <select class="form-control" name="type">
                                    @foreach (\App\Enums\NotificationType::all() as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span data-input="type" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Group</label>
                            <div class="select-block">
                                <select class="form-control" name="group">
                                    @foreach (\App\Enums\NotificationGroup::all() as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span data-input="group" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Resource Class</label>
                            <input type="text" class="form-control" name="notifiable_type">
                            <span data-input="notifiable_type" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Resource ID</label>
                            <input type="text" class="form-control" name="notifiable_id">
                            <span data-input="notifiable_id" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary text-dark">Cancel</a>
    </form>
@endsection
