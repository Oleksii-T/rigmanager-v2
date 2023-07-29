@extends('layouts.admin.app')

@section('title', 'Import Page')

@section('content_header')
    <x-admin.title
        text="Import #{{$import->id}}"
    />
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>User</label>
                        <a href="{{route('admin.users.edit', $import->user)}}" class="form-control">{{$import->user->name}}</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" value="{{$import->status}}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Posts</label>
                        <input type="text" class="form-control" value="{{count($import->posts??[])}}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Updated At</label>
                        <input type="text" class="form-control" value="{{$import->updated_at->format(env('ADMIN_DATETIME_FORMAT'))}}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Created At</label>
                        <input type="text" class="form-control" value="{{$import->created_at->format(env('ADMIN_DATETIME_FORMAT'))}}" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="posts-table" class="table table-bordered table-striped">
                <x-admin.posts-table />
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/posts.js')}}"></script>
@endpush
