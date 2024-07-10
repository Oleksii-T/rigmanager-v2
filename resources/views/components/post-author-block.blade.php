
<div class="prod-author">
    <div class="prod-author-info">
        <img class="prod-author-ava" src="{{userAvatar($post->user)}}" alt="{{($post->user->name??'unknown user') . ' avatar'}}">
        <div class="prod-author-about">
            <div class="prod-author-name">
                <a href="{{route('users.show', $post->user)}}" class="orange">
                    {{$post->user->name}}
                </a>
            </div>
            <a href="{{route('search', ['author'=>$post->user->slug])}}" class="prod-author-link">
                {{__('ui.allAuthorPosts')}}
            </a>
            @auth
                <br>
                @if ($post->user_id != $currentUser->id)
                    @if ($currentUser->mailers()->where('filters->author', $post->user_id)->first())
                        <a class="prod-author-link">{{__('ui.mailerAuthorAlreadyAdded')}}</a>
                    @else
                        <form action="{{route('mailers.store')}}" method="post" class="general-ajax-submit">
                            @csrf
                            <input type="hidden" name="filters[author]" value="{{$post->user_id}}">
                            <button type="submit" class="prod-author-link btn-as-link">{{__('ui.mailerAddAuthor')}}</button>
                        </form>
                    @endif
                @endif
            @else
                <form action="{{route('mailers.store')}}" method="post" class="general-ajax-submit">
                    @csrf
                    <input type="hidden" name="filters[author]" value="{{$post->user_id}}">
                    <button type="submit" class="prod-author-link btn-as-link">{{__('ui.mailerAddAuthor')}}</button>
                </form>
            @endauth
        </div>
    </div>
    <a href="#" data-url="{{route('users.contacts', $post->user)}}" class="button button-light show-contacts">
        {{__('ui.showContacts')}}
    </a>
    <br>
    @if ($currentUser?->id == $post->user_id)
        <button class="button button-light send-message-to-self">{{__('ui.chat')}}</button>
    @else
        @if ($hasChat)
            <a href="{{route('profile.chat')}}?chat_with={{$post->user_id}}" class="button button-light">{{__('ui.chat')}}</a>
        @else
            <a 
                href="#" 
                data-url="{{route('profile.chat.store', $post->user->slug)}}" 
                data-user="{{$currentUser?->isSub() ? $post->user->name : maskedUserName()}}" 
                class="button button-light send-message"
            >
                {{__('ui.chat')}}
            </a>
        @endif
    @endif
    @if ($post->is_tba)
        <br>
        <button
            class="button execute-tba button-light"
            data-url="{{route('posts.price-request', $post)}}"
            data-uname="{{$currentUser?->isSub() ? $post->user->name : maskedUserName()}}"
            data-ptitle="{{$post->title}}"
        >
            @lang('ui.askForCost')
        </button>
    @endif
    @if ($currentUser && $post->user_id==$currentUser->id)
        <br>
        <a href="{{route('posts.edit', $post)}}" class="button button-light">{{__('ui.edit')}}</a>
    @endif
</div>
