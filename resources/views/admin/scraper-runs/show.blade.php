@extends('layouts.admin.app')

@section('title', 'Scrapers')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="float-left">
                    <h1 class="m-0">Scraper Run #{{$scraperRun->id}}</h1>
                </div>
                <div class="float-left pl-3">
                    <a href="{{route('admin.scrapers.show', $scraperRun->scraper)}}" class="btn btn-primary">
                        Back to '{{$scraperRun->scraper->name}}' scraper
                    </a>
                    @if ($scraperPostToPublish && $scraperRun->status == \App\Enums\ScraperRunStatus::SUCCESS)
                        <a href="{{route('admin.scraper-posts.publishing', $scraperPostToPublish)}}" class="btn btn-primary">
                            Got to publishing
                        </a>
                        <a href="{{route('admin.scraper-runs.extra', $scraperRun)}}" class="btn btn-primary">
                            Find Extra Posts
                        </a>
                    @endif
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
                    <h3 class="card-title">General</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Scraping Status</label>
                                <input name="name" type="text" class="form-control" value="{{$scraperRun->status->readable()}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Scraped / Saved / Published / Max posts</label>
                                <input
                                    name="name"
                                    type="text"
                                    class="form-control"
                                    value="{{$scraperRun->scraped}} / {{$scraperRun->posts()->count()}} / {{$scraperRun->posts()->where('status', \App\Enums\ScraperPostStatus::PUBLISHED)->count()}} / {{$scraperRun->max}}"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Duration</label>
                                <input name="name" type="text" class="form-control" value="{{$scraperRun->created_at->adminFormat()}} - {{$scraperRun->end_at?->adminFormat()}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Posts</h3>
                </div>
                <div class="card-body">
                    <table id="scraper-run-posts-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Url</th>
                                <th>Data</th>
                                <th>Created at</th>
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
    <a href="{{ route('admin.scrapers.show', $scraperRun->scraper) }}" class="btn btn-outline-secondary text-dark min-w-100">Back to scraper</a>

    <x-admin.trivias />

    <div class="modal fade" id="log-full-text-modal" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Full Cell data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow:auto"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/scrapers.js')}}?v={{time()}}"></script>
@endpush

{{--

https://www.heavyoilfieldtrucks.com/listings/

post        .auto-listings-items .auto-listing
post_link   .summary .title a
title       .listing .title
description .description
--}}

