@extends('layouts.admin.app')

@section('title', 'Subscriptions')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="float-left">
                    <h1 class="m-0">Subscriptions</h1>
                </div>
                <div class="float-left pl-3">
                    <a href="{{route('admin.subscriptions.create')}}" class="btn btn-primary">
                        + Add Subscription
                    </a>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#subcs-trivia">
                        Trivia
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table id="subscriptions-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>User Name</th>
                                <th>Subscription Plan</th>
                                <th>Status</th>
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

    <x-admin.trivias />
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/subscriptions.js')}}"></script>
@endpush
