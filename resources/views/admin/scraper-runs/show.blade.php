@extends('layouts.admin.app')

@section('title', 'Scrapers')

@section('content_header')
    <x-admin.title
        text="Scraper Run {{$scraperRun->id}}"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Posts</h3>
                </div>
                <div class="card-body">
                    <table id="scraper-run-posts-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Data</th>
                                <th>Created at</th>
                                <th class="actions-column-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Logs</h3>
                </div>
                <div class="card-body">
                    <table id="scraper-run-logs-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Text</th>
                                <th>Data</th>
                                <th>Created at</th>
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
    <script src="{{asset('/js/admin/scrapers.js')}}"></script>
@endpush
