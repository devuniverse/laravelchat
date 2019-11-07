@php
$authId = auth()->id();
@endphp
@if ($messages)
    @foreach ($messages as $key => $message)
    <?php $whoseisIt = $message->sender_id == Auth::user()->id ? 'mine':''; ?>

    <li class="clearfix animated zoomIn {{ $whoseisIt }}" id="{{ Crypt::encryptString($message->id) }}">
      <div class="message-data @if ($message->sender_id === $authId)
           sent text-right
      @else
          received
      @endif ">
        <span class="message-data-time">{{date('d-m-Y h:i A' ,strtotime($message->created_at))}}</span>
      </div>
      <div class="message @if ($message->sender_id === $authId)
           my-message
      @else
          other-message float-right
      @endif"> {{ $message->message }} </div>
      <div class="message-action">
        @if ($message->sender_id === $authId)
            <i class="fa fa-ellipsis-h fa-2x pull-right" aria-hidden="true">
                <div class="delete" data-id="{{$message->id}}">Delete</div>
            </i>
        @else
            <i class="fa fa-ellipsis-h fa-2x pull-left" aria-hidden="true">
                <div class="delete" data-id="{{$message->id}}">Delete</div>
            </i>
        @endif
      </div>
    </li>

    @endforeach
@endif
