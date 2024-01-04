@extends('layouts.admin.app')

@section('title', 'User #{{ $user->id }}')

@section('content_header')
    <x-admin.title text="User #{{ $user->id }}" />
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ userAvatar($user) }}" alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{ $user->name }} ({{ $user->country }})</h3>
                    <p class="text-muted text-center">{{ $user->email }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Last Online</b> <a class="float-right">{{ $user->last_active_at->diffForHumans() }}</a>
                        </li>
                        <li class="list-group-item" title="total - notVisible">
                            <b>Posts</b>
                            <a class="float-right">{{ $user->posts()->count() }} - {{ $user->posts()->visible(false)->count() }}</a>
                        </li>
                    </ul>
                    @if ($currentUser->id != $user->id)
                        <a href="{{ route('admin.users.login', $user) }}" class="btn btn-primary btn-block"><b>Log In</b></a>
                    @endif
                </div>
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Information</h3>
                </div>

                <div class="card-body">
                    <strong><i class="fas fa-book mr-1"></i>Contact Emails</strong>
                    <p class="text-muted">{{ implode(', ', $info->emails ?? ['-']) }}</p>
                    @if ($info->phones)
                        <hr>
                        <strong><i class="fas fa-phone mr-1"></i>Contact Phones</strong>
                        <p class="text-muted">{{ implode(', ', $info->phones) }}</p>
                    @endif
                    @if ($info->website)
                        <hr>
                        <strong><i class="fa fa-globe mr-1"></i>Web site</strong>
                        <p class="text-muted">{{ $info->website }}</p>
                    @endif
                    @if ($info->linkedin)
                        <hr>
                        <strong><i class="fa fa-linkedin mr-1"></i>Linked In</strong>
                        <p class="text-muted">{{ $info->linkedin }}</p>
                    @endif
                    @if ($info->facebook)
                        <hr>
                        <strong><i class="fa fa-facebook mr-1"></i>Facebook</strong>
                        <p class="text-muted">{{ $info->facebook }}</p>
                    @endif
                    @if ($info->bio)
                        <hr>
                        <strong><i class="far fa-file-alt mr-1"></i>Bio</strong>
                        <p class="text-muted">{{ $info->bio }}</p>
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
                                Activity <strong>{{ $user->activitiesBy()->count() }}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#posts" data-toggle="tab">
                                Posts <strong>{{ $user->posts()->count() }}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#chats" data-toggle="tab">
                                Chats <strong>{{ count($user->getChats()) }}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#mailers" data-toggle="tab">
                                Mailers <strong>{{ $user->mailers()->count() }}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#subscriptions" data-toggle="tab">
                                Subscription <strong>{{ $user->subscriptions()->count() }}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#feedbacks" data-toggle="tab">
                                Feedbacks <strong>{{ $user->feedbacks()->count() }}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#imports" data-toggle="tab">
                                Imports <strong>{{ $user->imports()->count() }}</strong>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#notifications" data-toggle="tab">
                                Notifications <strong>{{ $user->notifications()->count() }}</strong>
                            </a>
                        </li>
                        @if ($currentUser->id != $user->id)
                            <li class="nav-item">
                                <a class="nav-link" href="#chat" data-toggle="tab">
                                    Direct Chat <strong>{{$unreadInChat}}</strong>
                                </a>
                            </li>
                        @endif
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
                                            <canvas id="user-activity" data-url="{{ route('admin.users.get-chart', [$user, 'user-activity']) }}"></canvas>
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

                        <div class="tab-pane" id="chats">
                            <table data-url="?table=messages" class="table table-bordered table-striped messages-content">
                            </table>
                        </div>

                        <div class="tab-pane" id="mailers">
                            <table data-url="?table=mailers" id="mailers-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="ids-column">ID</th>
                                        <th>User</th>
                                        <th>Title</th>
                                        <th>Active</th>
                                        <th>Posts Emailed #</th>
                                        <th>Last Mail At</th>
                                        <th>Created_at</th>
                                        <th class="actions-column-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="subscriptions">
                            <table data-url="?table=subscriptions" id="subscriptions-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="ids-column">ID</th>
                                        <th>User Name</th>
                                        <th>Subscription Plan</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                        <th class="actions-column-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="feedbacks">
                            <table data-url="?table=feedbacks" id="feedbacks-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="ids-column">Id</th>
                                        <th>User</th>
                                        <th>Subject</th>
                                        <th>Text</th>
                                        <th>IP</th>
                                        <th class="actions-column-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="imports">
                            <table data-url="?table=imports" id="imports-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="ids-column">ID</th>
                                        <th>User</th>
                                        <th>Status</th>
                                        <th>Posts</th>
                                        <th>Created_at</th>
                                        <th class="actions-column-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane" id="notifications">
                            <table data-url="?table=notifications" id="notifications-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="ids-column">Id</th>
                                        <th>User</th>
                                        <th>Text</th>
                                        <th>Read</th>
                                        <th>Created at</th>
                                        <th class="actions-column-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        @if ($currentUser->id != $user->id)
                            <div class="tab-pane" id="chat">
                                <div class="card direct-chat direct-chat-primary">
                                    {{--
                                    <div class="card-header">
                                        <h3 class="card-title">Direct Chat</h3>
                                        <div class="card-tools">
                                            <span title="3 New Messages" class="badge badge-primary">3</span>
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                                                <i class="fas fa-comments"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    --}}

                                    <div class="card-body">
                                        <div class="direct-chat-messages">
                                            @foreach ($chat as $message)
                                                <div class="direct-chat-msg {{$message->user_id == $currentUser->id ? 'right' : ''}}">
                                                    <div class="direct-chat-infos clearfix" style="{{$message->user_id == $currentUser->id ? 'margin-left:20%' : 'width:80%'}}">
                                                        <span class="direct-chat-name {{$message->user_id == $currentUser->id ? 'float-right' : 'float-left'}} {{$message->is_read ? 'orange' : ''}}">
                                                            {{$message->user->name}}
                                                        </span>
                                                        <span class="direct-chat-timestamp {{$message->user_id == $currentUser->id ? 'float-left' : 'float-right'}}">
                                                            {{$message->created_at->adminFormat()}}
                                                            <small>({{$message->created_at->diffForHumans()}})</small>
                                                        </span>
                                                    </div>

                                                    <img class="direct-chat-img" src="{{userAvatar($message->user)}}" alt="message user image">

                                                    <div class="direct-chat-text" style="{{$message->user_id == $currentUser->id ? 'margin-left:20%' : 'width:76.5%'}}">
                                                        {{$message->message}}
                                                    </div>
                                                </div>
                                            @endforeach


                                            {{--
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left">Alexander Pierce</span>
                                                    <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                                                </div>

                                                <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">

                                                <div class="direct-chat-text">
                                                    Is this template really for free? That's unbelievable!
                                                </div>
                                            </div>

                                            <div class="direct-chat-msg right">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-right">Sarah Bullock</span>
                                                    <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                                                </div>

                                                <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">

                                                <div class="direct-chat-text">
                                                    You better believe it!
                                                </div>
                                            </div>
                                            --}}
                                        </div>

                                        {{--
                                        <div class="direct-chat-contacts">
                                            <ul class="contacts-list">
                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img" src="dist/img/user1-128x128.jpg"
                                                            alt="User Avatar">
                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Count Dracula
                                                                <small class="contacts-list-date float-right">2/28/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">How have you been? I was...</span>
                                                        </div>

                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img" src="dist/img/user7-128x128.jpg"
                                                            alt="User Avatar">
                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Sarah Doe
                                                                <small class="contacts-list-date float-right">2/23/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">I will be waiting for...</span>
                                                        </div>

                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img" src="dist/img/user3-128x128.jpg"
                                                            alt="User Avatar">
                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Nadia Jolie
                                                                <small class="contacts-list-date float-right">2/20/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">I'll call you back at...</span>
                                                        </div>

                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img" src="dist/img/user5-128x128.jpg"
                                                            alt="User Avatar">
                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Nora S. Vans
                                                                <small class="contacts-list-date float-right">2/10/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">Where is your new...</span>
                                                        </div>

                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img" src="dist/img/user6-128x128.jpg"
                                                            alt="User Avatar">
                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                John K.
                                                                <small class="contacts-list-date float-right">1/27/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">Can I take a look at...</span>
                                                        </div>

                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#">
                                                        <img class="contacts-list-img" src="dist/img/user8-128x128.jpg"
                                                            alt="User Avatar">
                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name">
                                                                Kenneth M.
                                                                <small class="contacts-list-date float-right">1/4/2015</small>
                                                            </span>
                                                            <span class="contacts-list-msg">Never mind I found...</span>
                                                        </div>

                                                    </a>
                                                </li>

                                            </ul>

                                        </div>
                                        --}}
                                    </div>

                                    <div class="card-footer">
                                        <form action="{{route('admin.messages.store')}}" method="post" class="send-direct-message">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{$currentUser->id}}">
                                            <input type="hidden" name="reciever_id" value="{{$user->id}}">
                                            <div class="input-group">
                                                <input type="text" name="message" placeholder="Type Message ..." class="form-control" required>
                                                <span class="input-group-append">
                                                    <button type="submit" class="btn btn-primary">Send</button>
                                                </span>
                                            </div>
                                        </form>
                                        @if ($unreadInChat)
                                            <form action="{{route('admin.messages.read')}}" method="post" class="general-ajax-submit">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                                <input type="hidden" name="reciever_id" value="{{$currentUser->id}}">
                                                <button type="submit" class="btn btn-primary">Read Messages</button>
                                            </form>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-admin.trivias />

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
    <script src="{{ asset('js/admin/messages.js') }}"></script>
    <script src="{{ asset('js/admin/posts.js') }}"></script>
    <script src="{{ asset('js/admin/mailers.js') }}"></script>
    <script src="{{ asset('js/admin/subscriptions.js') }}"></script>
    <script src="{{ asset('js/admin/feedbacks.js') }}"></script>
    <script src="{{ asset('js/admin/imports.js') }}"></script>
    <script src="{{ asset('js/admin/notifications.js') }}"></script>
    <script>
        var userId = '{{ $user->id }}';
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
        const NUMBER_CFG = {
            count: DATA_COUNT,
            min: 0,
            max: 100
        };
        var detailedLogsTable = null;
        var dataForDetailedLogsTable = {
            causer_type: 'App\\Models\\User',
            causer_id: {},
            log_name: null,
            ts_date: null
        };

        $(document).ready(function() {
            // set up object to store all charts
            let charts = {
                userActivity: {
                    elem: $('#user-activity'),
                    hasDatasets: true,
                    chart: new Chart($('#user-activity'), {
                        type: 'bar',
                        data: {
                            datasets: []
                        },
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

                    let chartEvent = charts[key].chart.getElementsAtEventForMode(e, 'nearest', {
                        intersect: true
                    }, true);

                    if (!chartEvent.length) {
                        return;
                    }

                    // get the bar
                    const firstPoint = chartEvent[0];
                    const value = charts[key].chart.data.datasets[firstPoint.datasetIndex].data[firstPoint
                        .index];

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
                        order: [
                            [0, "desc"]
                        ],
                        serverSide: true,
                        ajax: {
                            url: '/admin/activity-logs',
                            data: function(filter) {
                                for (const key in dataForDetailedLogsTable) {
                                    filter[key] = dataForDetailedLogsTable[key];
                                }
                            }
                        },
                        columns: [{
                                data: 'id',
                                name: 'id',
                                searchable: false
                            },
                            {
                                data: 'log_name',
                                name: 'log_name'
                            },
                            {
                                data: 'event',
                                name: 'event'
                            },
                            {
                                data: 'causer',
                                name: 'causer'
                            },
                            {
                                data: 'subject',
                                name: 'subject'
                            },
                            {
                                data: 'description',
                                name: 'description'
                            },
                            {
                                data: 'properties',
                                name: 'properties',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'created_at',
                                name: 'created_at',
                                searchable: false
                            },
                        ]
                    });
                })
            }

            initFirstTab();

            $(document).on('change', '[name="eng-period"]', function(e) {
                initFirstTab();
            })

            $('.send-direct-message').submit(function(e) {
                e.preventDefault();
                loading();
                let form = $(this);
                $('.input-error').empty();

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: (response)=>{
                        window.location.reload();
                    },
                    error: function(response) {
                        swal.close();
                        showServerError(response);
                    }
                });
            })
        });

        function initFirstTab() {
            let el = $('#analytics');
            $.ajax({
                data: {
                    table: 'analytics',
                    date: $('[name="eng-period"]').val()
                },
                success: (response) => {
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
                data: {
                    period
                },
                success: (response) => {
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
