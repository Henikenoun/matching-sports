<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getConversations()
    {
        $userId = Auth::id();
        $conversations = Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with('messages')
            ->get();

        return response()->json($conversations);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Broadcast the message to other users in the conversation
        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_one_id' => 'required|exists:users,id',
            'user_two_id' => 'required|exists:users,id',
        ]);

        $conversation = Conversation::create([
            'user_one_id' => $request->user_one_id,
            'user_two_id' => $request->user_two_id,
        ]);

        return response()->json($conversation, 201);
    }
}