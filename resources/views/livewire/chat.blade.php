<div>
    @if ($chats)
        <div class="profile">
            <div class="profile-side chat-list">
                @foreach ($chats as $recId => $chat)
                    <div class="chat-el {{$selected==$recId ? 'active' : ''}}">
                        <div class="chat-avatar" style="background-image:url({{$chat['user']['avatar']}})"></div>
                        <div class="chat-main-info-wrpr">
                            <div class="chat-main-info">
                                <div class="chat-user-name">
                                    <label for="input-{{$recId}}">
                                        {{$chat['user']['name']}}
                                    </label>
                                    <input type="radio" id="input-{{$recId}}" wire:model.live.debounce="selected" class="d-none" value="{{$recId}}">
                                </div>
                                <div class="chat-new-messages">
                                    {{$chat['unread']}}
                                </div>
                            </div>
                            <div class="chat-date">
                                <p style="margin-bottom: 0px">2 hours ago</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="profile-content" style="position: relative">
                <div wire:loading wire:target='selected' class="chat-loading-messages">
                    <div>
                        <img src="/icons/loading.svg" alt="loading">
                    </div>
                </div>
                <div class="chat-block">
                    @if ($messages == null)
                        <p>@lang('ui.chatSelectUser')</p>
                    @else
                        <div class="chat-content">
                            <div class="chat-messages">
                                @foreach ($messages as $m)
                                    @php
                                        $me = $currentUser->id == $m->user_id;
                                    @endphp
                                    <div class="chat-m {{$me ? 'chat-m-me' : ''}}">
                                        {{-- x-intersect="$wire.see({{$m->id}})" --}}
                                        <div class="chat-m-wrpr">
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
