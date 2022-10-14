@extends('layouts.admin.app')

@section('title', 'Imports')

@section('content_header')
    <x-admin.title
        text="Imports"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table id="imports-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Posts</th>
                                <th>Created_at</th>
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
    <script src="{{asset('/js/admin/imports.js')}}"></script>
@endpush
