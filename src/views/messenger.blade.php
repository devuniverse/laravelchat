@extends(Config::get('messenger.master_file_extend'))

@section('css-styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="/vendor/messenger/css/messenger.css"> -->
    <link rel="stylesheet" href="/vendor/messenger/css/custom.css">
@endsection

@section('content')
<div id="wrapper">

    @include('backend.partials.topnav')

    @include('backend.partials.sidebar')

    <div id="main-content">
        <div class="container-fluid">
            @include('backend.partials.blockheader')
            @include('partials.appmessages')

            @section('content')
            <div class="row clearfix">
                <div class="col-lg-12 left-threads">
                    <div class="card chat_app">
                        <div class="people_list">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-magnifier"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Search...">
                            </div>
                            <ul class="list-unstyled threads chat-list mt-2 mb-0">
                                @include('messenger::partials.threads')
                            </ul>
                        </div>
                        <div class="chat">

                            <div class="chat-header clearfix">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                            <img src="/bck/assets/images/xs/avatar2.jpg" alt="avatar" />
                                        </a>
                                        <div class="chat-about">
                                            <h6 class="m-b-0">{{$withUser->name}}</h6>
                                            <small>Last seen: 2 hours ago</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 hidden-sm text-right">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-outline-secondary"><i class="icon-camera"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary"><i class="icon-camcorder"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-outline-info"><i class="icon-settings"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-outline-warning"><i class="icon-question"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="chat-history messenger clearfix">
                              @if( is_array($messages) )
                                 @if (count($messages) === 20)
                                     <div id="messages-preloader"></div>
                                 @endif

                                 <div id="messages-preloader"></div>
                             @else
                                 <p class="start-conv">Conversation started</p>
                             @endif
                             <ul class="m-b-0 messenger-body">
                               @include('messenger::partials.messages')
                             </ul>
                            </div>
                            <div class="chat-message clearfix">
                                <div class="input-group mb-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon-paper-plane"></i></span>
                                    </div>
                                    <input type="text" id="message-body" name="message" class="form-control" placeholder="Enter text here...">
                                </div>
                            </div>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
            @endsection
        </div>
    </div>

</div>
@endsection

@section('js-scripts')
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="text/javascript">
    var chatPrefix = "<?php echo $chatPrefix; ?>";
        var withId        = {{$withUser->id}},
            authId        = {{auth()->id()}},
            messagesCount = {{is_array($messages) ? count($messages) : '0'}};
            pusher        = new Pusher('{{config('messenger.pusher.app_key')}}', {
              cluster: '{{config('messenger.pusher.options.cluster')}}'
            });
    </script>
    <script src="/vendor/messenger/js/messenger-chat.js" charset="utf-8"></script>
@endsection
