@extends('layouts.admin.app')

@section('title', 'Mailers Logs')

@section('content_header')
    <x-admin.title
        text="Mailers Logs"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-2">
                            <select class="table-filter form-control" name="mailer">
                                <option value="">All Mailers</option>
                                @foreach (\App\Models\Mailer::all() as $m)
                                    <option value="{{$m->id}}" @selected(request()->mailer == $m->id)>{{$m->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="mailer-logs-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Mailer</th>
                                <th>Posts</th>
                                <th>Filters</th>
                                <th>Send at</th>
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
    <script src="{{asset('/js/admin/mailer-logs.js')}}"></script>
@endpush
