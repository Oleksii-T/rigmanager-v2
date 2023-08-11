@extends('layouts.admin.app')

@section('title', 'Create Partner')

@section('content_header')
    <x-admin.title
        text="Create Partner"
    />
@stop

@section('content')
    <form action="{{ route('admin.partners.store') }}" method="POST" class="general-ajax-submit">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                            <label>Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image">
                                <label class="custom-file-label">Choose document</label>
                            </div>
                            <img src="" alt="" class="custom-file-preview">
                            <span data-input="image" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Link</label>
                            <input name="link" type="text" class="form-control">
                            <span data-input="link" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Order</label>
                            <input name="order" type="text" class="form-control">
                            <span data-input="order" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control select2" name="user_id">
                                <option value="">None</option>
                                @foreach (\App\Models\User::all() as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="user_id" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
