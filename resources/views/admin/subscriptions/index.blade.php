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

    <div class="modal fade" id="subcs-trivia" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Activily log trivia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Subscription logic explanation:
                    <ul>
                        <li>Logic contains: "Subscription Plans", "Subscriptions" and "Subscription Cycles".</li>
                        <li>"Subscriptions" belongs to "Subscription plan".</li>
                        <li>"Subscription Cycles" belongs to "Subscription". E.g. user makes monthly sub, each month "Subscription Cycle" will be created (subscription entity stays unchanged).</li>
                        <li>Sub plans are synced with Stripe when created and updated.</li>
                        <li>Sub plans prices can not be changed via site. Only manual editting.</li>
                        <li>
                            Sub creation:<br>
                            firstly, we are creating new default payment method (with 3DS check).<br>
                            Then, subscription is created and payed via default payment method (just created).<br>
                            Pending payments are allowed - sub may be created as incomplete - 1 day user can use sub functionalities - after 1 day of incomplete sub - it will be automaticaly canceled.
                        </li>
                        <li>When user cancels sub, we leave last cycle as active - so paid functionalities are available untill last payed period expired.</li>
                        <li>If user makes new subscription when active cycle is still on, we deactivate current cycle (with respec sub) and create new sub with new active cycle.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/subscriptions.js')}}"></script>
@endpush
