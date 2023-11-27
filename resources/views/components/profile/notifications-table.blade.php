<div class="history-table" style="margin-bottom:20px">
    <table class="notifications-table">
        @forelse ($notifications as $notification)
            <tr>
                <td class="notif-table-icon">
                    @if ($notification->type == \App\Enums\NotificationType::INFO)
                        @svg('icons/notification-info.svg')
                    @elseif ($notification->type == \App\Enums\NotificationType::SUCCESS)
                        @svg('icons/notification-success.svg')
                    @elseif ($notification->type == \App\Enums\NotificationType::WARNING)
                        @svg('icons/notification-warning.svg')
                    @elseif ($notification->type == \App\Enums\NotificationType::DANGER)
                        @svg('icons/notification-danger.svg')
                    @endif
                </td>
                <td>
                    {!!$notification->text!!}
                </td>
                <td>
                    {{$notification->created_at->format('M d, Y H:i')}}
                </td>
                <td>
                    @if (!$notification->is_read)
                        <form action="{{route('notifications.read', $notification)}}" method="post" class="notification-read">
                            @csrf
                            <button class="btn-as-link gray-t" type="submit">Read</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
                    No Notifications found
                </td>
            </tr>
        @endforelse
    </table>
</div>
<div class="pagination-field">
    {{ $notifications->appends(request()->input())->links() }}
</div>
