@extends('layouts.admin.app')

@section('title', 'mailers')

@section('content_header')
    <x-admin.title
        text="mailers"
        :button="['+ Add mailer', route('admin.mailers.create')]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="mailers-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>User</th>
                                <th>Title</th>
                                <th>Active</th>
                                <th>Posts Emailed #</th>
                                <th>Last Mail At</th>
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
    <script src="{{asset('/js/admin/mailers.js')}}"></script>
@endpush
