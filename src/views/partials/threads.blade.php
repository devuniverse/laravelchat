@foreach ($threads as $key => $thread)
    <li class="@if (
        !$thread->lastMessage->is_seen &&
        $thread->lastMessage->sender_id != auth()->id()
    )
        unseen
    @endif
    @if ($thread->withUser->id === $withUser->id)
        active
    @endif">
        @if ($thread->lastMessage)
            <a href="/{{ $chatPrefix }}/messenger/t/{{ \Crypt::encryptString($thread->withUser->id) }}" class="thread-link">
                <img src="/bck/assets/images/xs/avatar1.jpg" alt="avatar" />
                <div class="about">
                  <div class="name">{{$thread->withUser->name}}</div>
                  <div class="lastmessage">
                    @if ($thread->lastMessage->sender_id === auth()->id())
                        <i class="fa fa-reply" aria-hidden="true"></i>
                    @endif
                    {{substr($thread->lastMessage->message, 0, 20)}}
                  </div>
                  <div class="status"> <i class="fa fa-circle offline"></i> left 7 mins ago </div>
                </div>
            </a>
        @endif
    </li>
@endforeach
