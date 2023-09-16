@extends('layouts.admin.app')

@section('title', 'Notification')

@section('content_header')
    <x-admin.title
        text="Notification"
        :button="['+ Add Notification', route('admin.notifications.create')]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="is_read">
                                <option value="">Select status</option>
                                <option value="1">Read</option>
                                <option value="0">Not read</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="notifications-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">Id</th>
                                <th>User</th>
                                <th>Text</th>
                                <th>Read</th>
                                <th>Created at</th>
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
    <script src="{{asset('/js/admin/notifications.js')}}"></script>
@endpush
