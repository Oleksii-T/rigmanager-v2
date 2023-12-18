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
            <div class="card-header">
                <h5 class="m-0">Basic Info</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                            <label>Avatar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="user[avatar]">
                                <label class="custom-file-label">{{$user->avatar->original_name ?? 'Choose File'}}</label>
                            </div>
                            <img src="{{$user->avatar->url??''}}" alt="" class="custom-file-preview">
                            <span data-input="user.avatar" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                            <label>Banner</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="user[banner]">
                                <label class="custom-file-label">{{$user->banner->original_name ?? 'Choose File'}}</label>
                            </div>
                            <img src="{{$user->banner->url??''}}" alt="" class="custom-file-preview">
                            <span data-input="user.banner" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="user[name]" type="text" class="form-control" value="{{$user->name}}">
                            <span data-input="user.name" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <input name="user[slug]" type="text" class="form-control" value="{{$user->slug}}">
                            <span data-input="user.slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="user[email]" class="form-control" value="{{$user->email}}">
                            <span data-input="user.email" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User Roles</label>
                            <select class="form-control select2" name="user[roles][]" multiple>
                                @foreach (\App\Models\Role::all() as $role)
                                    <option value="{{$role->id}}" @selected($user->roles->contains('id', $role->id))>{{readable($role->name)}}</option>
                                @endforeach
                            </select>
                            <span data-input="user.roles" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Country</label>
                            <select class="form-control select2" name="user[country]">
                                @foreach (countries() as $key => $name)
                                    <option value="{{$key}}" @selected($user->country == $key)>{{$name}}</option>
                                @endforeach
                            </select>
                            <span data-input="user.country" class="input-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input name="user[password]" type="password" class="form-control">
                            <span data-input="user.password" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input name="user[password_confirm]" type="password" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Additional Info</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="info[bio]" class="form-control">{{$info->bio}}</textarea>
                            <span data-input="info.bio" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" name="info[website]" class="form-control" value="{{$info->website}}">
                            <span data-input="info.website" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facebook</label>
                            <input type="text" name="info[facebook]" class="form-control" value="{{$info->facebook}}">
                            <span data-input="info.facebook" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>LinkedIn</label>
                            <input type="text" name="info[linkedin]" class="form-control" value="{{$info->linkedin}}">
                            <span data-input="info.linkedin" class="input-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Contact Emails <small>(json)</small></label>
                            <input name="info[emails]" type="text" class="form-control" value="{{json_encode($info->emails)}}">
                            <span data-input="info.emails" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Contact Phones <small>(json)</small></label>
                            <input name="info[phones]" type="text" class="form-control" value="{{json_encode($info->phones)}}">
                            <span data-input="info.phones" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
