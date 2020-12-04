<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use App\Message;
use User;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages()
    {
    return Message::with('user')->get();
    }


    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
      $user = Auth::user();

      Log::info($request->message);

      $model = new Message();
      $model->user_id = $user->id;
      $model->message = $request->message;
    
     $model->save();
    
      broadcast(new MessageSent($user, $request->message))->toOthers();
    
      return ['status' => 'Message Sent!'];
    }

}
