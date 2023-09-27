<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-2">
                <select class="table-filter form-control select2" name="user_id">
                    <option value="">Select user</option>
                    @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="messages-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Users</th>
                    <th>Total</th>
                    <th>Unread</th>
                    <th>Last Message</th>
                    <th>Last At</th>
                    <th class="actions-column-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chats as $chat)
                    <tr>
                        <td>
                            @foreach ($chat['users'] as $user)
                                <a href="{{route('admin.users.show', $user)}}">{{$user->name}}</a>
                                <br>
                            @endforeach
                        </td>
                        <td>{{$chat['count']}}</td>
                        <td>{{$chat['unread']}}</td>
                        <td>{{$chat['last_message']}}</td>
                        <td>{{$chat['last_at']}}</td>
                        <td>
                            <div class="table-actions d-flex align-items-center">
                                <a href="{{route("admin.messages.show", $chat['uids'])}}" class="btn btn-info btn-sm mr-1">Show</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
