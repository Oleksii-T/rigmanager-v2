@extends('layouts.admin.app')

@section('title', 'Activity Logs')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="float-left">
                    <h1 class="m-0">Activity Logs</h1>
                </div>
                <div class="float-left pl-3">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#alog-trivia">
                        Trivia
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
                    <div class="row">
                        <div class="col-lg-2">
                            <select class="table-filter form-control has-sub-select" name="log_name">
                                <option value="">Select Name</option>
                                @foreach ($names as $name => $events)
                                    <option value="{{$name}}">{{$name}}</option>
                                @endforeach
                            </select>
                            @foreach ($names as $name => $events)
                                <select class="table-filter form-control d-none" data-parentselect="log_name" data-parentvalue="{{$name}}" name="event[{{$name}}]">
                                    <option value="">Select {{$name}} event</option>
                                    @foreach ($events as $event)
                                        <option value="{{$event}}">{{$event}}</option>
                                    @endforeach
                                </select>
                            @endforeach
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control has-sub-select" name="causer_type">
                                <option value="">Select Causer</option>
                                @foreach ($causers as $causer => $ids)
                                    <option value="{{$causer}}">{{$causer}}</option>
                                @endforeach
                            </select>
                            @foreach ($causers as $causer => $ids)
                                <select class="table-filter form-control d-none" data-parentselect="causer_type" data-parentvalue="{{$causer}}" name="causer_id[{{$causer}}]">
                                    <option value="">Select {{$causer}} ID</option>
                                    @foreach ($ids as $id)
                                        <option value="{{$id}}">{{$id}}</option>
                                    @endforeach
                                </select>
                            @endforeach
                        </div>
                        <div class="col-lg-2">
                            <select class="table-filter form-control has-sub-select" name="subject_type">
                                <option value="">Select Subject</option>
                                @foreach ($subjects as $causer => $ids)
                                    <option value="{{$causer}}">{{$causer}}</option>
                                @endforeach
                            </select>
                            @foreach ($subjects as $subject => $ids)
                                <select class="table-filter form-control d-none" data-parentselect="subject_type" data-parentvalue="{{$subject}}" name="subject_id[{{$subject}}]">
                                    <option value="">Select {{$subject}} ID</option>
                                    @foreach ($ids as $id)
                                        <option value="{{$id}}">{{$id}}</option>
                                    @endforeach
                                </select>
                            @endforeach
                        </div>
                        <div class="col-lg-2">
                            <input type="text" name="period" class="form-control table-filter daterangepicker-mult">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="activity-logs-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="ids-column">ID</th>
                                <th>Name</th>
                                <th>Event</th>
                                <th>Causer</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Properties</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-admin.triavias />
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/activity-logs.js')}}"></script>
@endpush
