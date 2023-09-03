@extends('layouts.admin.app')

@section('title', 'Blog')

@section('content_header')
    <x-admin.title
        text="Blog"
        :button="['+ Add Blog', route('admin.blogs.create')]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="status">
                                <option value="">Select status</option>
                                @foreach (\App\Enums\BlogStatus::all() as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="blogs-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">Id</th>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Posted at</th>
                                <th class="actions-column-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/blogs.js')}}"></script>
@endpush
