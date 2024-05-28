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
                <div class="card-body">
                    <table id="activity-logs-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Name</th>
                                <th>Event</th>
                                <th>Causer</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Properties</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>                      
                    </table>
                </div>
            </div>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add-views">
                Add View(s)
            </button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary text-dark">Cancel</a>
        </div>
    </div>

    <div class="modal fade" id="add-views">
        <div class="modal-dialog">
            <form action="{{route('admin.posts.add-views', $post)}}" method="post" class="modal-content general-ajax-submit">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Add Views to post #{{$post->id}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" class="form-control" name="amount" value="1">
                                <span data-input="amount" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control daterangepicker-single" name="date">
                                <span data-input="date" class="input-error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/posts.js')}}?v={{time()}}"></script>
@endpush