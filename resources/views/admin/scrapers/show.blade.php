@extends('layouts.admin.app')

@section('title', 'Create Scraper')

@section('content_header')
    <x-admin.title
        text="Scraper #{{$scraper->id}} '{{$scraper->name}}'"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @if ($runInProgress)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Run {{$runInProgress->id}} in progress</h3>
                    </div>
                    <div class="card-body">

                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            @else
                <form action="{{route('admin.scraper-runs.store')}}" method="post" class="card general-ajax-submit">
                    @csrf
                    <input type="hidden" name="scraper_id" value="{{$scraper->id}}">
                    <input type="hidden" name="scraper_debug_enabled" value="0">
                    <input type="hidden" name="only_count" value="0">
                    <div class="card-header">
                        <h3 class="card-title">Create Run</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Scrape limit</label>
                                    <input name="scrape_limit" type="number" class="form-control">
                                    <span data-input="scrape_limit" class="input-error"></span>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>-</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="scraper_debug_enabled" value="1" id="scraper_debug_enabled">
                                        <label class="form-check-label" for="scraper_debug_enabled">Scraper Debug Enables</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="only_count" value="1" id="only_count">
                                        <label class="form-check-label" for="only_count">Only count</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>-</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sanitize_html" value="1" checked id="sanitize_html">
                                        <label class="form-check-label" for="sanitize_html">Sanitize HTML</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="ignore_page_error" value="1" id="ignore_page_error">
                                        <label class="form-check-label" for="ignore_page_error">Ignore page errors</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success min-w-100">Run</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Runs</h3>
                </div>
                <div class="card-body">
                    <table id="scraper-runs-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Scraping Status</th>
                                <th>Scraped/Saved/Published/Max Posts</th>
                                <th>Started_at</th>
                                <th>Ended_at</th>
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
    <a href="{{ route('admin.scrapers.edit', $scraper) }}" class="btn btn-outline-secondary text-dark min-w-100">Go to Edit</a>
    <a href="{{ route('admin.scrapers.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Back to scrapers</a>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/scrapers.js')}}?v={{time()}}"></script>
@endpush
