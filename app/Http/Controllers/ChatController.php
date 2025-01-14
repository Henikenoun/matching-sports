<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            'user_one_id' => (int) $request->user_one_id,
            'user_two_id' => (int) $request->user_two_id,
        ]);

        return response()->json($conversation, 201);
    }

    public function getConversationById($id)
    {
        $conversation = Conversation::with('messages')->find($id);

        if ($conversation) {
            return response()->json($conversation);
        } else {
            return response()->json(['error' => 'Conversation not found'], 404);
        }
    }

    public function getConversationByUserIds(Request $request)
{
    $request->validate([
        'user_one_id' => 'required|exists:users,id',
        'user_two_id' => 'required|exists:users,id',
    ]);

    $userOneId = (int) $request->user_one_id;
    $userTwoId = (int) $request->user_two_id;

    Log::info('Fetching conversation for users', [
        'user_one_id' => $userOneId,
        'user_two_id' => $userTwoId,
    ]);

    $conversation = Conversation::where(function ($query) use ($userOneId, $userTwoId) {
        $query->where('user_one_id', $userOneId)
              ->where('user_two_id', $userTwoId);
    })->orWhere(function ($query) use ($userOneId, $userTwoId) {
        $query->where('user_one_id', $userTwoId)
              ->where('user_two_id', $userOneId);
    })->first();

    if ($conversation) {
        Log::info('Conversation found', ['conversation' => $conversation]);
        return response()->json($conversation);
    } else {
        Log::warning('Conversation not found for users, creating new conversation', [
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);

        // Create a new conversation
        $conversation = Conversation::create([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);

        Log::info('New conversation created', ['conversation' => $conversation]);
        return response()->json($conversation, 201);
    }
}
}