<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Resources\CreatedMessageResource;
use App\Interfaces\ChatRoomRepositoryInterface;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function __construct(private ChatRoomRepositoryInterface $chatRoomRepository) {}

    public function index(ChatRoom $chatRoom): Collection
    {
        return ChatMessage::query()
            ->where('chat_room_id', $chatRoom->id)
            ->with('user:id,name')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function store(Request $request, int $roomId)
    {
        // TODO: move to FormRequest file
        $validated = $request->validate([
            'text' => 'required|string|max:255',
            'friend_id' => 'nullable|integer|exists:users,id',
        ]);

        [$chatRoom, $newRoom] = $this->chatRoomRepository
            ->findOrCreate($roomId, $validated['friend_id'] ?? null);

        $message = ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => auth()->user()->id,
            'text' => $validated['text'],
        ]);

        broadcast(new MessageSent($message));

        return new CreatedMessageResource($message, $newRoom);
    }
}
