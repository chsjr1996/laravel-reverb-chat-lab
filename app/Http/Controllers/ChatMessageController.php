<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function index(ChatRoom $chatRoom): Collection
    {
        return ChatMessage::query()
            ->where('chat_room_id', $chatRoom->id)
            ->orderBy('id', 'asc')
            ->get();
    }

    public function store(Request $request, ChatRoom $chatRoom)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $message = ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => auth()->user()->id,
            'text' => $validated['text'],
        ]);

        broadcast(new MessageSent($message));

        return $message;
    }
}
