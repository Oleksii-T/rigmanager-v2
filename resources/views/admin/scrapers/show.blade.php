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
                    <div class="card-header">
                        <h3 class="card-title">Create Run</h3>
                    </div>
                    <div class="card-body">
                        
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
                                <th>Scraped/Saved/Max Posts</th>
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
