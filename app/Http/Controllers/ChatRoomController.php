<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;

class ChatRoomController extends Controller
{
    public function show(User $friend): InertiaResponse|ResponseFactory
    {
        return inertia('Chat', [
            'friend' => $friend,
        ]);
    }

    public function index(User $friend): Collection
    {
        return ChatMessage::query()
            ->where(fn ($query) => $query
                ->where('sender_id', auth()->id())
                ->orWhere('receiver_id', $friend->id)
            )
            ->orWhere(fn ($query) => $query
                ->where('sender_id', $friend->id)
                ->orWhere('receiver_id', auth()->id())
            )
            ->with(['sender', 'receiver'])
            ->orderBy('id', 'asc')
            ->get();
    }

    public function store(Request $request, User $friend)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $friend->id,
            'text' => $validated['text'],
        ]);

        broadcast(new MessageSent($message));

        return $message;
    }
}
