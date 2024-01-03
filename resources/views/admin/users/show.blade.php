@extends('layouts.admin.app')

@section('title', 'User #{{$user->id}}')

@section('content_header')
    <x-admin.title text="User #{{$user->id}}" />
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{userAvatar($user)}}" alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{$user->name}} ({{$user->country}})</h3>
                    <p class="text-muted text-center">{{$user->email}}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Last Online</b> <a class="float-right">{{$user->last_active_at->diffForHumans()}}</a>
                        </li>
                        <li class="list-group-item" title="total - notVisible">
                            <b>Posts</b> <a class="float-right">{{$user->posts()->count()}} - {{$user->posts()->visible()->count()}}</a>
                        </li>
                    </ul>
                    <a href="{{route('admin.users.login', $user)}}" class="btn btn-primary btn-block"><b>Log In</b></a>
                </div>
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Information</h3>
                </div>

                <div class="card-body">
                    <strong><i class="fas fa-book mr-1"></i>Contact Emails</strong>
                    <p class="text-muted">{{implode(', ', $info->emails??['-'])}}</p>
                    @if ($info->phones)
                        <hr>
                        <strong><i class="fas fa-phone mr-1"></i>Contact Phones</strong>
                        <p class="text-muted">{{implode(', ', $info->phones)}}</p>
                    @endif
                    @if ($info->website)
                        <hr>
                        <strong><i class="fa fa-globe mr-1"></i>Web site</strong>
                        <p class="text-muted">{{$info->website}}</p>
                    @endif
                    @if ($info->linkedin)
                        <hr>
                        <strong><i class="fa fa-linkedin mr-1"></i>Linked In</strong>
                        <p class="text-muted">{{$info->linkedin}}</p>
                    @endif
                    @if ($info->facebook)
                        <hr>
                        <strong><i class="fa fa-facebook mr-1"></i>Facebook</strong>
                        <p class="text-muted">{{$info->facebook}}</p>
                    @endif
                    @if ($info->bio)
                        <hr>
                        <strong><i class="far fa-file-alt mr-1"></i>Bio</strong>
                        <p class="text-muted">{{$info->bio}}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2" style="border-bottom: 2px solid #6c757d">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#analytics" data-toggle="tab">
                                Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#activity" data-toggle="tab">
                                Activity <strong>{{$user->activitiesBy()->count()}}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#posts" data-toggle="tab">
                                Posts <strong>{{$user->posts()->count()}}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#messages" data-toggle="tab">
                                Messages <strong>{{$user->messages()->count()}}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#mailers" data-toggle="tab">
                                Mailers <strong>{{$user->mailers()->count()}}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#subscriptions" data-toggle="tab">
                                Subscription <strong>{{$user->subscriptions()->count()}}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#feedbacks" data-toggle="tab">
                                Feedbacks <strong>{{$user->feedbacks()->count()}}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#imports" data-toggle="tab">
                                Imports <strong>{{$user->imports()->count()}}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#notifications" data-toggle="tab">
                                Notifications <strong>{{$user->notifications()->count()}}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#chat" data-toggle="tab">
                                Chat <strong>?</strong>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="analytics">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label title="Engagement is calculated based on activity logs amount caused by user.">
                                            Engagement
                                            <span class="help-tooltip-icon" data-toggle="modal" data-target="#engagement-trivia">
                                                @svg('icons/info.svg')
                                            </span>
                                        </label>
                                        <span class="form-control eng-info-wrpr">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date Filter</label>
                                        <input type="text" name="eng-period" class="form-control daterangepicker-mult">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="activity">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                Overall activity <small>click on bar to see detailed logs</small>
                                            </h3>
                                            <div class="card-tools" style="width: 250px">
                                                <div class="input-group">
                                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#alog-trivia">
                                                        Trivia
                                                    </a>
                                                    <input type="text" name="period" class="form-control daterangepicker-mult">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="user-activity" data-url="{{route('admin.users.get-chart', [$user, 'user-activity'])}}"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="d-none" data-toggle="modal" data-target="#alog-details-modal"></button>
                        </div>

                        <div class="tab-pane" id="posts">
                            <table id="posts-table" data-url="?table=posts" class="table table-bordered table-striped">
                                <x-admin.posts-table />
                            </table>
                        </div>

                        <div class="tab-pane messages-content" id="messages">
                            <table id="messages-table" data-url="?table=messages" class="table table-bordered table-striped messages-content">
                            </table>
                        </div>

                        <div class="tab-pane" id="mailers">
                            todo: mailers
                        </div>

                        <div class="tab-pane" id="subscriptions">
                            todo: subscriptions
                        </div>

                        <div class="tab-pane" id="feedbacks">
                            todo: feedbacks
                        </div>

                        <div class="tab-pane" id="imports">
                            todo: imports
                        </div>

                        <div class="tab-pane" id="notifications">
                            todo: notifications
                        </div>

                        <div class="tab-pane" id="chat">
                            todo: chat
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-admin.triavias />

    <div class="modal fade" id="alog-details-modal" style="display: none;">
        <div class="modal-dialog" style="width:90%;max-width:none;">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Activity Log details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="activity-log-details-table" class="table table-bordered table-striped">
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
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/messages.js')}}"></script>
    <script src="{{asset('/js/admin/posts.js')}}"></script>
    <script>
        var userId = '{{$user->id}}';
        var colors = [
            '#FF0000', // Bright Red
            '#0000FF', // Deep Blue
            '#00FF00', // Vivid Green
            '#FFFF00', // Bright Yellow
            '#800080', // Deep Purple
            '#FFA500', // Orange
            '#87CEEB', // Sky Blue
            '#FFC0CB', // Pink
            '#006400', // Dark Green
            '#D2691E', // Chocolate Brown
            '#007BA7', // Cerulean
            '#FF00FF', // Magenta
        ];
        const DATA_COUNT = 7;
        const NUMBER_CFG = {count: DATA_COUNT, min: 0, max: 100};
        var detailedLogsTable = null;
        var dataForDetailedLogsTable = {
            causer_type: 'App\\Models\\User',
            causer_id: {},
            log_name: null,
            ts_date: null
        };

        $(document).ready(function () {
            // set up object to store all charts
            let charts = {
                userActivity: {
                    elem: $('#user-activity'),
                    hasDatasets: true,
                    chart: new Chart($('#user-activity'), {
                        type: 'bar',
                        data: {datasets: []},
                        options: {
                            scales: {
                                y: {
                                    stacked: true,
                                    beginAtZero: true,
                                    min: 0,
                                    ticks: {
                                        stepSize: 1,
                                    }
                                },
                                x: {
                                    stacked: true,
                                    type: 'time',
                                    time: {
                                        unit: 'day',
                                    }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        footer: (tooltipItems) => {
                                            let sum = 0;
                                            tooltipItems.forEach(function(tooltipItem) {
                                                sum += tooltipItem.parsed.y;
                                            });
                                            return 'Sum: ' + sum;
                                        },
                                    }
                                }
                            }
                        },
                    })
                }
                //! if you are adding more charts, note the click event below which initializes for each chart via loop
            };

            // initial draw of charts
            for (const key in charts) {
                // set up event to update chart data whet period updated
                charts[key].elem.closest('.card').find('input[name=period]').change(function(e) {
                    drawChart(charts[key]);
                });

                drawChart(charts[key]);

                // show detailed log in bar click
                charts[key].elem.click(function(e) {
                    e.preventDefault();

                    let chartEvent = charts[key].chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);

                    if (!chartEvent.length) {
                        return;
                    }

                    // get the bar
                    const firstPoint = chartEvent[0];
                    const value = charts[key].chart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];

                    // show modal
                    $('[data-target="#alog-details-modal"]').trigger('click');

                    // consract data
                    dataForDetailedLogsTable.log_name = value.log_name;
                    dataForDetailedLogsTable.causer_id[dataForDetailedLogsTable.causer_type] = userId;
                    dataForDetailedLogsTable.ts_date = value.x;

                    // redraw existing datatable
                    if (detailedLogsTable) {
                        detailedLogsTable.draw();
                        return;
                    }

                    // init data table
                    detailedLogsTable = $('#activity-log-details-table').DataTable({
                        order: [[ 0, "desc" ]],
                        serverSide: true,
                        ajax: {
                            url: '/admin/activity-logs',
                            data: function (filter) {
                                for (const key in dataForDetailedLogsTable) {
                                    filter[key] = dataForDetailedLogsTable[key];
                                }
                            }
                        },
                        columns: [
                            { data: 'id', name: 'id', searchable: false },
                            { data: 'log_name', name: 'log_name' },
                            { data: 'event', name: 'event' },
                            { data: 'causer', name: 'causer'},
                            { data: 'subject', name: 'subject'},
                            { data: 'description', name: 'description'},
                            { data: 'properties', name: 'properties', orderable: false, searchable: false},
                            { data: 'created_at', name: 'created_at', searchable: false},
                        ]
                    });
                })
            }

            initFirstTab();

            $(document).on('change', '[name="eng-period"]', function (e) {
                initFirstTab();
            })
        });

        function initFirstTab(){
            let el = $('#analytics');
            $.ajax({
                data: {
                    table: 'analytics',
                    date: $('[name="eng-period"]').val()
                },
                success: (response)=>{
                    $('.eng-info-wrpr').html(response.data.info);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function drawChart(chartItem) {
            let period = chartItem.elem.closest('.card').find('input[name=period]').val();
            $.ajax({
                url: chartItem.elem.data('url'),
                type: 'get',
                data: {period},
                success: (response)=>{
                    let data = response.data;

                    if (chartItem.hasDatasets) {
                        let i = 0;
                        for (const key in data) {
                            data[key].backgroundColor = colors[i];
                            data[key].borderColor = colors[i];
                            i++;
                        }

                        chartItem.chart.data.datasets = data;
                    } else if (chartItem.chart.config._config.type == 'pie') {
                        chartItem.chart.data.labels = Object.keys(data);
                        chartItem.chart.data.datasets[0].data = Object.values(data);
                    } else {
                        chartItem.chart.data.datasets[0].data = data;
                    }

                    chartItem.chart.update();
                },
                error: function(response) {
                    console.log(`chart data ajax error`, response);
                }
            });
        }

    </script>
@endpush
