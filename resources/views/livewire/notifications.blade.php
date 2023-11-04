<div class="header-cabinet header-bell">
    <button class="header-bell-icon">
        <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.5033 17.1454C12.0915 18.9392 10.6114 19.9404 9.13131 20C7.66314 20.0596 5.96818 19.1478 5.47282 17.1454C5.39523 17.1454 5.31765 17.1454 5.23409 17.1454C4.00465 17.1454 2.77521 17.1395 1.55173 17.1454C0.919105 17.1514 0.429715 16.919 0.149211 16.3409C-0.125325 15.7807 -0.0178978 15.2563 0.358097 14.7557C0.793773 14.1716 1.19961 13.5638 1.61141 12.9619C1.78449 12.7116 1.85014 12.4255 1.85014 12.1156C1.85014 10.4648 1.81433 8.81406 1.85611 7.16329C1.93369 4.12396 3.34815 1.89511 6.11738 0.625744C10.1638 -1.23957 14.9921 1.19785 15.9589 5.54231C16.0783 6.09058 16.132 6.66269 16.1379 7.22288C16.1618 8.84386 16.1499 10.4648 16.1439 12.0858C16.1439 12.4493 16.2394 12.7712 16.4483 13.0632C16.878 13.6651 17.2958 14.267 17.7195 14.8689C18.0418 15.3337 18.0895 15.8403 17.8389 16.3468C17.5822 16.8534 17.1525 17.1275 16.5796 17.1335C15.3084 17.1454 14.0312 17.1395 12.76 17.1395C12.6764 17.1454 12.5988 17.1454 12.5033 17.1454ZM16.5438 15.7032C16.1141 15.0834 15.7023 14.4815 15.2845 13.8915C14.8966 13.3433 14.7175 12.7414 14.7175 12.0739C14.7235 10.4768 14.7235 8.87962 14.7175 7.28248C14.7175 7.03814 14.7056 6.7938 14.6817 6.54946C14.3236 3.06317 10.874 0.709177 7.49006 1.65077C5.0073 2.34207 3.3004 4.559 3.28847 7.13349C3.2825 8.78427 3.2825 10.435 3.28847 12.0858C3.28847 12.7533 3.10346 13.3552 2.71552 13.9035C2.33953 14.4398 1.9695 14.9762 1.59351 15.5125C1.55173 15.5721 1.50995 15.6317 1.45624 15.7092C6.48741 15.7032 11.4947 15.7032 16.5438 15.7032ZM10.9934 17.1454C9.69231 17.1454 8.40319 17.1454 7.12003 17.1454C7.08422 17.1454 7.04842 17.1514 7.01261 17.1514C7.17972 17.8903 8.0451 18.534 8.90452 18.5697C9.84152 18.6055 10.7248 18.0095 10.9934 17.1454Z" fill="white"/>
        </svg>
        @if ($unreadCount > 0)
            <span class="notifications-total">{{$unreadCount}}</span>
        @endif
    </button>
    <ul class="notif-items">
        @if (count($notifications))
            @foreach ($notifications as $n)
                <li class="notif-item">
                    <div class="notif-text">
                        @if ($n['type'] == \App\Enums\NotificationType::INFO)
                            @svg('icons/notification-info.svg')
                        @elseif ($n['type'] == \App\Enums\NotificationType::SUCCESS)
                            @svg('icons/notification-success.svg')
                        @elseif ($n['type'] == \App\Enums\NotificationType::WARNING)
                            @svg('icons/notification-warning.svg')
                        @elseif ($n['type'] == \App\Enums\NotificationType::DANGER)
                            @svg('icons/notification-danger.svg')
                        @endif
                        <p>
                            {!!$n['text']!!}
                        </p>
                        </div>
                    <div class="notif-info">
                        <span>{{$n['created_at']}}</span>
                        @if (!$n['is_read'])
                            <button wire:click.once="read({{$n['id']}})">
                                <img wire:loading wire:target='read({{$n['id']}})' src="/icons/loading.svg" alt="loading">
                                @lang('ui.read')
                            </button>
                        @else
                            <div>
                                @svg('icons/tick.svg')
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach
            <li class="notif-item-more">
                <div class="notif-info">
                    @if ($hasMore)
                        <button wire:click="more">
                            @lang('ui.seeMore')
                            <img wire:loading wire:target='more' src="/icons/loading.svg" alt="loading">
                        </button>
                    @else
                        <div></div>
                    @endif
                    @if ($unreadCount)
                        <button wire:click="readAll">
                            <img wire:loading wire:target='readAll' src="/icons/loading.svg" alt="loading">
                            @lang('ui.readAll')
                        </button>
                    @else
                        <div>
                            @svg('icons/tick.svg')
                        </div>
                    @endif
                </div>
            </li>
        @else
            <li class="notif-item-more">
                <p>@lang('ui.notificationsEmpty')</p>
            </li>
        @endif
    </ul>
</div>
