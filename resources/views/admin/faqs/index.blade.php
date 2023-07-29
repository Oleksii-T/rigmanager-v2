@extends('layouts.admin.app')

@section('title', 'FAQs')

@section('content_header')
    <x-admin.title
        text="FAQs"
        :button="['+ Add FAQ', route('admin.faqs.create')]"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table id="faqs-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">Order</th>
                                <th>Question</th>
                                <th>Answer</th>
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
    <script src="{{asset('/js/admin/faqs.js')}}"></script>
@endpush
