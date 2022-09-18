@extends('layouts.admin.app')

@section('title', 'Partners')

@section('content_header')
    <x-admin.title
        text="Partners"
        :button="['+ Add Partner', route('admin.partners.create')]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table id="partners-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>User</th>
                                <th>Image</th>
                                <th>Link</th>
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
    <script src="{{asset('/js/admin/partners.js')}}"></script>
@endpush
