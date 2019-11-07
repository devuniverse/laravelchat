<?php

namespace Devuniverse\Laravelchat\Controllers;

use App\Http\Controllers\Controller;
use Pusher\Pusher;
use Crypt;
use Illuminate\Http\Request;
use Devuniverse\Laravelchat\Models\Message;
use Devuniverse\Laravelchat\Facades\Messenger;
use Devuniverse\Laravelchat\Models\Conversation;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['web', 'auth']);
    }
    public function messengerHome(){

      return view('messenger::home');
    }
    /**
     * Get messenger page.
     *
     * @param  int  $withId
     * @return Response
     */
    public function laravelMessenger(Request $request)
    {   
        $userId = Crypt::decryptString($request->id);
        Messenger::makeSeen(auth()->id(), $userId);
        $withUser = config('messenger.user.model', 'App\User')::findOrFail($userId);
        $messages = Messenger::messagesWith(auth()->id(), $withUser->id);
        $threads  = Messenger::threads(auth()->id());

        return view('messenger::messenger', ['withUser'=>$withUser, 'messages'=>$messages, 'threads'=>$threads]);
    }

    /**
     * Create a new message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Message::rules());

        $authId = auth()->id();
        $withId = $request->withId;
        $conversation = Messenger::getConversation($authId, $withId);

        if (! $conversation) {
            $conversation = Messenger::newConversation($authId, $withId);
        }

        $message = Messenger::newMessage($conversation->id, $authId, $request->message);
        // Pusher
        $pusher = new Pusher(
            config('messenger.pusher.app_key'),
            config('messenger.pusher.app_secret'),
            config('messenger.pusher.app_id'),
            [
                'cluster' => config('messenger.pusher.options.cluster')
            ]
        );
        $pusher->trigger('messenger-channel', 'messenger-event', [
            'message'    => $message,
            'senderId'   => $authId,
            'withId'     => $withId
        ]);
        $message['whoseisit']= ($authId !== $withId) ? 'mine' : 'none';
        return response()->json([
            'success' => true,
            'message' => $message,

        ], 200);
    }

    /**
     * Load threads view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response.
     */
    public function loadThreads(Request $request)
    {
        if ($request->ajax()) {
            $withUser = config('messenger.user.model', 'App\User')::findOrFail($request->withId);
            $threads  = Messenger::threads(auth()->id());
            $view     = view('messenger::partials.threads', compact('threads', 'withUser'))->render();

            return response()->json($view, 200);
        }
    }

    /**
     * Load more messages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response.
     */
    public function moreMessages(Request $request)
    {
        $this->validate($request, ['withId' => 'required|integer']);

        if ($request->ajax()) {
            $messages = Messenger::messagesWith(
                auth()->id(),
                $request->withId,
                $request->take
            );
            $view = view('messenger::partials.messages', compact('messages'))->render();

            return response()->json([
                'view'          => $view,
                'messagesCount' => $messages->count()
            ], 200);
        }
    }

    /**
     * Make a message seen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function makeSeen(Request $request)
    {
        Messenger::makeSeen($request->authId, $request->withId);

        return response()->json(['success' => true], 200);
    }

    /**
     * Delete a message.
     *
     * @param  int  $id
     * @return Response.
     */
    public function destroy(Request $request)
    {
        $confirm = Messenger::deleteMessage($request->id, auth()->id());

        return response()->json(['success' => true], 200);
    }
}
