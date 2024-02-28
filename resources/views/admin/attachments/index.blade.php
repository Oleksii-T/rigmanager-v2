@extends('layouts.admin.app')

@section('title', 'Attachments')

@section('content_header')
    <x-admin.title
        text="Attachments"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="attachmentable_type">
                                <option value="">Resource</option>
                                @foreach ($resources as $type)
                                    <option value="{{$type}}">{{$type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="attachments-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Name</th>
                                <th>Preview</th>
                                <th>Resource</th>
                                <th>Group</th>
                                <th>Size</th>
                                <th>Created At</th>
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
    <script src="{{asset('/js/admin/attachments.js')}}"></script>
@endpush
