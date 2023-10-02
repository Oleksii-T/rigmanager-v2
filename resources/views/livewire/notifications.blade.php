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
                            <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                                <g id="Warning / Info">
                                    <path id="Vector" d="M12 11V16M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21ZM12.0498 8V8.1L11.9502 8.1002V8H12.0498Z" stroke="#7272ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                            </svg>
                        @elseif ($n['type'] == \App\Enums\NotificationType::SUCCESS)
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px" viewBox="0 0 512 512" version="1.1">
                                <title>success</title>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="add-copy" fill="#7cff7c" transform="translate(42.666667, 42.666667)">
                                        <path d="M213.333333,3.55271368e-14 C95.51296,3.55271368e-14 3.55271368e-14,95.51296 3.55271368e-14,213.333333 C3.55271368e-14,331.153707 95.51296,426.666667 213.333333,426.666667 C331.153707,426.666667 426.666667,331.153707 426.666667,213.333333 C426.666667,95.51296 331.153707,3.55271368e-14 213.333333,3.55271368e-14 Z M213.333333,384 C119.227947,384 42.6666667,307.43872 42.6666667,213.333333 C42.6666667,119.227947 119.227947,42.6666667 213.333333,42.6666667 C307.43872,42.6666667 384,119.227947 384,213.333333 C384,307.43872 307.438933,384 213.333333,384 Z M293.669333,137.114453 L323.835947,167.281067 L192,299.66912 L112.916693,220.585813 L143.083307,190.4192 L192,239.335893 L293.669333,137.114453 Z" id="Shape">

                                        </path>
                                    </g>
                                </g>
                            </svg>
                        @elseif ($n['type'] == \App\Enums\NotificationType::WARNING)
                            <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="17" r="1" fill="#ffff7f"/>
                                <path d="M12 10L12 14" stroke="#ffff7f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3.44722 18.1056L10.2111 4.57771C10.9482 3.10361 13.0518 3.10362 13.7889 4.57771L20.5528 18.1056C21.2177 19.4354 20.2507 21 18.7639 21H5.23607C3.7493 21 2.78231 19.4354 3.44722 18.1056Z" stroke="#ffff7f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @elseif ($n['type'] == \App\Enums\NotificationType::DANGER)
                            <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 12C19.5 16.1421 16.1421 19.5 12 19.5C7.85786 19.5 4.5 16.1421 4.5 12C4.5 7.85786 7.85786 4.5 12 4.5C16.1421 4.5 19.5 7.85786 19.5 12ZM21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM11.25 13.5V8.25H12.75V13.5H11.25ZM11.25 15.75V14.25H12.75V15.75H11.25Z" fill="#ff7575"/>
                            </svg>
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
