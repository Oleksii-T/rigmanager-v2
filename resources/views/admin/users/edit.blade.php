@extends('layouts.admin.app')

@section('title', 'Edit User')

@section('content_header')
    <x-admin.title
        text="Edit User"
    />
@stop

@section('content')
    <form action="{{route('admin.users.update', $user)}}" method="POST" class="general-ajax-submit">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                            <label>Avatar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="avatar">
                                <label class="custom-file-label">{{$user->avatar->original_name ?? 'Choose File'}}</label>
                            </div>
                            <img src="{{$user->avatar->url??''}}" alt="" class="custom-file-preview">
                            <span data-input="avatar" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" value="{{$user->name}}">
                            <span data-input="name" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <input name="slug" type="text" class="form-control" value="{{$user->slug}}">
                            <span data-input="slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{$user->email}}">
                            <span data-input="email" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User Roles</label>
                            <select class="form-control select2" name="roles[]" multiple>
                                @foreach (\App\Models\Role::all() as $role)
                                    <option value="{{$role->id}}" @selected($user->roles->contains('id', $role->id))>{{readable($role->name)}}</option>
                                @endforeach
                            </select>
                            <span data-input="role" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Country</label>
                            <select class="form-control select2" name="country">
                                @foreach (countries() as $key => $name)
                                    <option value="{{$key}}" @selected($user->country == $key)>{{$name}}</option>
                                @endforeach
                            </select>
                            <span data-input="role" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="bio" class="form-control">{{$user->bio}}</textarea>
                            <span data-input="bio" class="input-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input name="password" type="password" class="form-control">
                            <span data-input="password" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input name="password_confirm" type="password" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
