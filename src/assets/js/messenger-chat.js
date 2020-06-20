/**
 * This JS file applies only for messenger page.
 */
;(function () {
    var take       = (messagesCount < 20) ? 0 : 20, // represents the number of messages to be displayed.
        $messenger = $('.messenger'),
        $loader    = $('#messages-preloader'),
        $entityslug    = (JSON.parse($('[name="entity"]').prop("content"))).entity ,
        channel    = pusher.subscribe('messenger-channel');

    /**
     * Do action when a message event is triggered.
     */
    channel.bind('messenger-event', function (data) {
        if (data.senderId == withId && data.withId == authId && data.withId != data.senderId) { // current conversation thread.
            newMessage(data.message, 'received');
            playTweet();
            makeSeen(authId, withId);
        }  else if (data.withId == authId && data.withId != data.senderId) { // not opened thread.
            playTweet();
            loadThreads();
        }
    });

    function addBlue(){
      $.each('.other-message', function(key,value){
        $(this).find('.fa-check').addClass('fa-seenblue');
      });
    }
    /**
     * Make a message seen.
     */
    function makeSeen(authId, withId) {
        $.ajax({
            url: '/'+chatPrefix+'/messenger/ajax/make-seen',
            method: 'POST',
            data: {authId: authId, withId: withId}
        }).done(function (res) {
            if (res.success) {
                loadThreads();
                $('.other-message').find('.fa-check').removeClass('fa-check').addClass('fa-check-circle faseen');
            }
        });
    }

    /**
     * Delete confirmation.
     */
    function confirmDelete() {
        return confirm('Are your sure you want to delete this message');
    }

    /**
     * Create a new menu for the new message.
     */
    function newMenu(messageClass, messageId) {
        var pull = (messageClass === 'sent') ? 'pull-right' : 'pull-left';

        return '\
            <i class="fa fa-ellipsis-h fa-2x '+ pull +'" aria-hidden="true">\
                <div class="delete" data-id="'+ messageId +'">Delete</div>\
            </i>\
        ';
    }

    /**
     * Append a new message to chat body.
     */
    function newMessage(message, messageClass, failed = 0) {

        $('.messenger-body').append('\
            <li class="clearfix animated '+ (message.whoseisit =='mine' ? 'slideInDown':'slideInUp')+' '+ message.whoseisit +'" id="'+message.convoid+'">\
                <div class="message-inner clearfix">'+
                '<div class="message-data ' + messageClass + '">'+
                    '<span class="message-data-time">'+message.timeago+'</span>'+
                '</div>'+
                '<div class="message '+( message.whoseisit=='mine' ? 'my-message':'other-message float-right')+'">' + message.message +' <i class="fa fa-check"></i></div>'+
                '<div class="message-action">'+ newMenu(messageClass, message.id) +'</div>'+
                '</div>'+
            '</li>'
          );
        if (failed) {
            $('.messenger-body').append('\
                <a class="unsent">\
                    <small>\
                        This message didn\'t send. Check your internet connection and try again.\
                    </small>\
                </a><br>\
            ');
        } else {
            $('#message-body').val('');
        }
        scrollMessagesDown();
    }

    /**
     * Scroll messages down to some height or bottom.
     */
    function scrollMessagesDown(height = 0) {
        var scrollTo = height || $messenger.prop('scrollHeight');

        // $messenger.scrollTop(scrollTo);
        $messenger.scrollTop(2000);
    }

    /**
     * Reload threads.
     */
    function loadThreads() {
        $.ajax({
            url: '/'+chatPrefix+'/messenger/threads',
            method: 'GET',
            data: {withId: withId}
        }).done(function (view) {
            $('.threads').html(view);
        });
    }

    /**
     * Load more messages.
     */
    function loadMessages() {
        $.ajax({
            url: '/'+chatPrefix+'/messenger/more/messages',
            method: 'GET',
            data: {
                withId: withId,
                take: take
            }
        }).done(function (res) {
            var prevHeight = $messenger.prop('scrollHeight');

            $('.messenger-body').html(res.view);
            var newHeight  = $messenger.prop('scrollHeight');
            scrollMessagesDown(newHeight - prevHeight); // stop at the current height.
            if (res.messagesCount < take) { // load no more messages.
                take = 0;
                $loader.after('<p class="start-conv">Conversation started</p>');
                $loader.remove();
            }
        });
    }

    /**
     * Play message notification sound.
     */
    function playTweet() {
        var audio = new Audio('/vendor/messenger/sounds/tweet.mp3');
        audio.volume = 0.1;
        audio.play();
    }

    $(document).ready(function () {
      setInterval(function() {
          loadThreads();
      }, 20 * 1000); // 60 * 1000 milsec

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="projefyxo"]').attr('content')
            }
        });

        scrollMessagesDown();

        function sendNewMessage(){
          var message = $('#message-body').val();
          if (message) {
              var JqHXR = $.ajax({
                  url: '/'+chatPrefix+'/messenger/send',
                  method: 'POST',
                  data: {
                      message: message,
                      withId: withId
                  }
              });
          }
          JqHXR.done(function (res) { // message sent.
              if (res.success) {
                  newMessage(res.message, 'sent');
                  loadThreads();
              }
          });
          JqHXR.fail(function (res) { // message didn't send.
              newMessage(res.message, 'sent', true);
          });
        }
        $("#message-body").keypress(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            //alert(code);
            if (code == 13) {
                sendNewMessage();

                return true;
            }
        });
        /**
         * Send message to backend and handle responses.
         */
        $(document).on('click', '#send-btn', function (e) {
          sendNewMessage();
        });

        /**
         * Load more messages when scroll to top.
         */
        $messenger.on('scroll', function (e) {
            if (!$messenger.scrollTop() && take) {
                take += 20;
                loadMessages();
            }
        });

        /**
         * Hover to messages to show menu dots.
         */
        $(document).on('mouseover', '.message-row', function (e) {
            $(this).find('.fa-ellipsis-h').show();
        });

        /**
         * Mouse up to remove menu dots.
         */
        $(document).on('mouseout', '.message-row', function (e) {
            var deleteBtn = $(this).find('.delete').css('display');

            if (deleteBtn !== 'block') {
                $(this).find('.fa-ellipsis-h').hide(); // only hide if delete popup is not poped up.
            }
        });

        /**
         * CLick on menu dots to show up delete message option.
         */
        $(document).on('click', '.fa-ellipsis-h', function (e) {
            // Hide all other opened menus.
            $('.delete').not($(this).find('.delete')).hide();
            $('.fa-ellipsis-h').not($(this).find('.fa-ellipsis-h')).hide();
            // Only show this menu.
            $(this).find('.delete').toggle();
        });

        /**
         * Hide opened delete menu when click anywhere.
         */
        $('body').on('click', function (e) {
            if ($(e.target).hasClass('fa-ellipsis-h')) { // toggle delete menu on clicking on dots.
                return true;
            } else if ($(e.target).hasClass('message-row')) { // don't hide on hover dots when click on message row.
                $('.fa-ellipsis-h').not($(e.target).find('.fa-ellipsis-h')).hide();
            } else {
                $('.fa-ellipsis-h').hide();
            }
            $('.delete').hide();
        });

        /**
         * Delete message.
         */
        $(document).on('click', '.delete', function (e) {
          $(this).prepend('<i class="fa fa-spinner fa-spin"></i>');
          var id       = $(this).attr('data-id'),
              $message = $(this).parent().parent();

          $.ajax({
              url: '/'+chatPrefix+'/messenger/delete/' + id,
              method: 'DELETE'
          }).done(function (res) {
              if (res.success) {
                  $message.hide(function () {
                      $message.remove();
                  });
              }
          });
        });
    });

}());
