@php
$authId = auth()->id();
@endphp
@if ($messages)
    @foreach ($messages as $key => $message)
    <?php $whoseisIt = $message->sender_id == Auth::user()->id ? 'mine':'';
    ?>

    <li class="clearfix animated zoomIn {{ $whoseisIt }}" id="{{ Crypt::encryptString($message->id) }}">
      <div class="message-inner">
        <div class="message-data @if ($message->sender_id === $authId)
             sent text-right
        @else
            received
        @endif ">
          <span class="message-data-time">{{ $message->created_at->diffForHumans() }}</span>
        </div>
        <div class="message @if ($message->sender_id === $authId)
             my-message  float-right
        @else
            other-message
        @endif"> {{ $message->message }} <i class="fa @if($message->is_seen)fa-check-circle faseen @else fa-check @endif"></i></div>
        <div class="message-action">
          @if ($message->sender_id === $authId)
              <div class="fa fa-ellipsis-h fa-2x pull-left" aria-hidden="true">
                  <div class="delete" data-id="{{$message->id}}"><i class="fa fa-times"></i></div>
              </div>
          @else
              <div class="fa fa-ellipsis-h fa-2x pull-right" aria-hidden="true">
                  <div class="delete" data-id="{{$message->id}}"><i class="fa fa-times"></i></div>
              </div>
          @endif
        </div>
      </div>
    </li>

    @endforeach
@endif
