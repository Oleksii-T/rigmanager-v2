@extends('layouts.admin.app')

@section('title', 'Scrapers')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="float-left">
                    <h1 class="m-0">Extra Scraper Run {{$scraperRun->id}}</h1>
                </div>
                <div class="float-left pl-3">
                    <a href="{{route('admin.scraper-runs.show', $scraperRun)}}" class="btn btn-primary">
                        Back to '{{$scraperRun->id}}' run
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
                <div class="card-header">
                    <h3 class="card-title">Author`s Previously Scraped Posts which was not found in this run</h3>
                </div>
                <div class="card-body">
                    <table id="scraper-run-extra-posts-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Url</th>
                                <th>Title</th>
                                <th>Created at</th>
                                <th class="actions-column">Actions</th>
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
    <script src="{{asset('/js/admin/scrapers.js')}}?v={{time()}}"></script>
@endpush

