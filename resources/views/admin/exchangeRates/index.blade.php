@extends('layouts.admin.app')

@section('title', 'Exchange Rates')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="float-left">
                    <h1 class="m-0">Exchange Rates</h1>
                </div>
                <div class="float-left pl-3">
                    <a href="{{route('admin.exchange-rates.create')}}" class="btn btn-primary">
                        + Add Rate
                    </a>
                </div>
                <div class="float-left pl-3">
                    <form action="{{route('admin.exchange-rates.sync-rates')}}" method="post" class="general-ajax-submit">
                        @csrf
                        <button class="btn btn-primary">
                            Sync Rates with API
                        </button>
                    </form>
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
                    <table id="exchange-rates-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Cost</th>
                                <th>Auto Update</th>
                                <th>Updated At</th>
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
    <script src="{{asset('/js/admin/exchange-rates.js')}}"></script>
@endpush
