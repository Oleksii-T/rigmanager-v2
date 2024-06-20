@extends('layouts.admin.app')

@section('title', 'Create User')

@section('content_header')
    <x-admin.title
        text="Create User"
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                            <label>Avatar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="user[avatar]">
                                <label class="custom-file-label">Choose File</label>
                            </div>
                            <img src="" alt="" class="custom-file-preview">
                            <span data-input="user.avatar" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                            <label>Banner</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="user[banner]">
                                <label class="custom-file-label">Choose File</label>
                            </div>
                            <img src="" alt="" class="custom-file-preview">
                            <span data-input="user.banner" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="user[name]" type="text" class="form-control">
                            <span data-input="user.name" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <input name="user[slug]" type="text" class="form-control">
                            <span data-input="user.slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="user[email]" class="form-control">
                            <span data-input="user.email" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User Roles</label>
                            <select class="form-control select2" name="user[roles][]" multiple>
                                @foreach (\App\Models\Role::all() as $role)
                                    <option value="{{$role->id}}">{{readable($role->name)}}</option>
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
                                    <option value="{{$key}}">{{$name}}</option>
                                @endforeach
                            </select>
                            <span data-input="user.country" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input name="user[password]" type="text" class="form-control">
                            <span data-input="user.password" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Email is verified</label>
                            <div class="checkbox">
                                <label>
                                    <input name="verify_email_now" type="radio" value="1" checked>
                                    yes
                                    <small>(without mail)</small>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input name="verify_email_now" type="radio" value="2">
                                    yes
                                    <small>(send "welcome email" mail)</small>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input name="verify_email_now" type="radio" value="3">
                                    no
                                    <small>(without mail)</small>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input name="verify_email_now" type="radio" value="4">
                                    no
                                    <small>(send "verify email" mail)</small>
                                </label>
                            </div>
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
                            <textarea name="info[bio]" class="form-control"></textarea>
                            <span data-input="info.bio" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" name="info[website]" class="form-control">
                            <span data-input="info.website" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facebook</label>
                            <input type="text" name="info[facebook]" class="form-control">
                            <span data-input="info.facebook" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>LinkedIn</label>
                            <input type="text" name="info[linkedin]" class="form-control">
                            <span data-input="info.linkedin" class="input-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Contact Emails <small>(json)</small></label>
                            <input name="info[emails]" type="text" class="form-control">
                            <span data-input="info.emails" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Contact Phones <small>(json)</small></label>
                            <input name="info[phones]" type="text" class="form-control">
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
