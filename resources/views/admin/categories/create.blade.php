@extends('layouts.admin.app')

@section('title', 'Create User')

@section('content_header')
    <x-admin.title
        text="Create User"
        bcRoute="admin.users.create"
    />
@stop

@section('content')
    <form action="{{ route('admin.users.store') }}" method="POST" class="general-ajax-submit">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Basic Info</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4 user-image-block">
                    <label class="uploader mr-3 show-uploaded-file-preview">
                        <input type="file" name="avatar" class="sr-only" id="avatar">
                        <img src="{{asset('img/empty-avatar.jpeg')}}" class="custom-file-preview" alt="" style="width: 30px">
                    </label>
                    <button type="button" class="btn btn-default" data-trigger="#avatar">Change Photo</button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" placeholder="Name...">
                            <span data-input="name" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Email Address...">
                            <span data-input="email" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User Roles</label>
                            <select class="form-control select2" name="roles[]" multiple>
                                @foreach (\App\Models\Role::all() as $role)
                                    <option value="{{$role->id}}">{{readable($role->name)}}</option>
                                @endforeach
                            </select>
                            <span data-input="role" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Set Password</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input name="password" type="password" class="form-control" placeholder="Password...">
                            <span data-input="password" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
