<div>
    @if ($chats)
        <div class="profile">
            <div class="profile-side chat-list">
                @foreach ($chats as $recId => $chat)
                    <input type="radio" x-ref="input{{$recId}}" wire:model.live.debounce="chatWith" class="d-none" value="{{$recId}}">
                    <div class="chat-el {{$chatWith==$recId ? 'active' : ''}}" x-on:click="$refs.input{{$recId}}.click()">
                        <div class="chat-avatar" style="background-image:url({{$chat['user']['avatar']}})"></div>
                        <div class="chat-main-info-wrpr">
                            <div class="chat-main-info">
                                <div class="chat-user-name">
                                    {{$chat['user']['name']}}
                                </div>
                                @if ($chat['unread'])
                                    <div class="chat-new-messages" title="@lang('ui.chatYouHaveUnded')">
                                        {{$chat['unread']}}
                                    </div>
                                @else
                                    @if ($chat['reciever_seen'])
                                        <div class="chat-rreaded-messages" title="@lang('ui.chatRecieverSeenAll')">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke="#ff8d12" d="M4 9.00005L10.2 13.65C11.2667 14.45 12.7333 14.45 13.8 13.65L20 9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path stroke="#ff8d12" d="M3 9.17681C3 8.45047 3.39378 7.78123 4.02871 7.42849L11.0287 3.5396C11.6328 3.20402 12.3672 3.20402 12.9713 3.5396L19.9713 7.42849C20.6062 7.78123 21 8.45047 21 9.17681V17C21 18.1046 20.1046 19 19 19H5C3.89543 19 3 18.1046 3 17V9.17681Z"stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="chat-rreaded-messages" title="@lang('ui.chatRecieverNotSeenAll')">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke="#ff8d12" d="M4 7.00005L10.2 11.65C11.2667 12.45 12.7333 12.45 13.8 11.65L20 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <rect stroke="#ff8d12" x="3" y="5" width="18" height="14" rx="2" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="chat-date">
                                <p style="margin-bottom: 0px">{{$chat['last_at']}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="profile-content" style="position: relative">
                <div wire:loading wire:target='chatWith' class="chat-loading-messages">
                    <div>
                        <img src="/icons/loading.svg" alt="loading">
                    </div>
                </div>
                <div class="chat-block">
                    @if ($messages == null)
                        <p>@lang('ui.chatSelectUser')</p>
                    @else
                        <div class="chat-content">
                            {{-- $el.scrollTop = $el.scrollHeight --}}
                            <div id="msgs-to-scroll" class="chat-messages" x-effect="setTimeout(() => {document.getElementById('msgs-to-scroll').scrollTop = document.getElementById('msgs-to-scroll').scrollHeight}, 50);" >
                                @foreach ($messages as $m)
                                    @php
                                        $me = $currentUser->id == $m->user_id;
                                    @endphp
                                    <div class="chat-m {{$me ? 'chat-m-me' : ''}}">
                                        <div class="chat-m-wrpr"> {{-- x-intersect="$wire.see({{$m->id}})" --}}
                                            <div class="chat-m-user">
                                                @if (!$me)
                                                    <div class="chat-m-user-avatar" style="background-image:url({{$chat['user']['avatar']}})"></div>
                                                @endif
                                                <span class="chat-m-user-name">{{$m->user->name}}</span>
                                                @if ($me)
                                                    <div class="chat-m-user-avatar" style="background-image:url({{userAvatar()}})"></div>
                                                @endif
                                            </div>
                                            <div class="chat-m-text">{{$m->message}}</div>
                                            <p class="chat-m-date">
                                                {{$m->created_at->diffForHumans()}}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <form wire:submit.debounce="sendMessage" class="chat-send-form">
                                <div>
                                    <textarea type="text" wire:model="message" placeholder="Type a message..."></textarea>
                                    <button type="submit" class="header-button loading-el" >
                                        <span class="loading-el-l">
                                            <img src="/icons/loading.svg" alt="loading">
                                        </span>
                                        <span class="loading-el-og">
                                            @lang('ui.chatSend')
                                        </span>
                                    </button>
                                    @error('message')
                                        <div>
                                            <span class="input-error">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <p>
            @lang('ui.chatEmpty')
        </p>
    @endif
</div>
