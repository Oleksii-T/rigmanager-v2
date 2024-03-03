@extends('layouts.admin.app')

@section('title', 'Posts')

@section('content_header')
    <x-admin.title
        text="Posts"
        :button="['+ Add Post', route('admin.posts.create')]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-2 mb-2">
                            <select class="table-filter form-control select2" name="user_id">
                                <option value="">User Filter</option>
                                @foreach (\App\Models\User::all() as $filter)
                                    <option value="{{$filter->id}}">{{$filter->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control select2" name="category_id">
                                <option value="">Category Filter</option>
                                @foreach (\App\Models\Category::all() as $filter)
                                    <option value="{{$filter->id}}">{{$filter->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control select2" name="group">
                                <option value="">Group Filter</option>
                                @foreach (\App\Enums\PostGroup::all() as $key=> $name)
                                    <option value="{{$key}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="status">
                                <option value="">Status Filter</option>
                                @foreach (\App\Models\Post::STATUSES as $filter)
                                    <option value="{{$filter}}">{{readable($filter)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="type">
                                <option value="">Type Filter</option>
                                @foreach (\App\Enums\PostType::all() as $key => $name)
                                    <option value="{{$key}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="condition">
                                <option value="">Condition Filter</option>
                                @foreach (\App\Models\Post::CONDITIONS as $filter)
                                    <option value="{{$filter}}">{{readable($filter)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="duration">
                                <option value="">Duration Filter</option>
                                @foreach (\App\Models\Post::DURATIONS as $filter)
                                    <option value="{{$filter}}">{{readable($filter)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="is_active">
                                <option value="">Active Filter</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="is_urgent">
                                <option value="">Urgent Filter</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="is_import">
                                <option value="">Import\Export Filter</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <a href="{{request()->url()}}" class="btn btn-warning">Clear Filters</a>

                            @if (\App\Models\Post::status('pending')->exists())
                                <form action="{{route('admin.posts.approve-all')}}" method="post" class="general-ajax-submit" style="display: inline-block">
                                    @csrf
                                    <button class="btn btn-warning">Mark All as Approved</button>
                                </form>
                            @endif

                            <a href="{{route('admin.posts.start-approving')}}" class="btn btn-warning start-approving">Start Approving</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="posts-table" class="table table-bordered table-striped">
                        <x-admin.posts-table />
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/posts.js')}}?v={{time()}}"></script>
@endpush
