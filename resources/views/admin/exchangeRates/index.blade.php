@extends('layouts.admin.app')

@section('title', 'Exchange Rates')

@section('content_header')
    <x-admin.title
        text="Exchange Rates"
        :button="['+ Add Rate', route('admin.exchange-rates.create')]"
    />
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
