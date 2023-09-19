<div class="profile">
    <div class="profile-side chat-list">
        @foreach ($chats as $recId => $chat)
            <div class="chat-el {{$selected==$recId ? 'active' : ''}}">
                <div class="chat-avatar">
                    <img src="/icons/emptyAva.svg" alt="">
                </div>
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
            Loading messages...
        </div>
        <div class="chat-block">
            @if ($messages == null)
                <p>Please select chat</p>
            @else
                <div class="chat-content">
                    <div class="chat-messages">
                        @foreach ($messages as $m)
                            <div class="chat-m {{$currentUser->id == $m->user_id ? 'chat-m-me' : ''}}">
                                @if ($currentUser->id == $m->user_id)
                                    <div class="chat-m-empty"></div>
                                @endif
                                {{-- x-intersect="$wire.see({{$m->id}})" --}}
                                <div class="chat-m-wrpr">
                                    <p class="chat-m-user">
                                        {{$m->user->name}}
                                    </p>
                                    <div class="chat-m-text">{{$m->message}}</div>
                                    <p class="chat-m-date">
                                        {{$m->created_at->diffForHumans()}}
                                    </p>
                                </div>
                                @if ($currentUser->id != $m->user_id)
                                    <div class="chat-m-empty"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <form wire:submit.debounce="sendMessage" class="chat-send-form">
                        <textarea type="text" wire:model="message" placeholder="Type a message..."></textarea>
                        <button type="submit" class="header-button loading-el">
                            <span class="loading-el-l">
                                Sending...
                            </span>
                            <span class="loading-el-og">
                                Send
                            </span>
                        </button>
                        @error('message')
                            <div>
                                <span class="input-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
