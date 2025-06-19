<?php

namespace App\Http\Controllers;

use App\Events\ChatCreated;
use App\Events\MessageSent;
use App\Http\Requests\MessageStoreRequest;
use App\Http\Resources\CreatedMessageResource;
use App\Interfaces\ChatMessageRepositoryInterface;
use App\Interfaces\ChatRoomRepositoryInterface;
use App\Models\ChatRoom;
use Illuminate\Support\Collection;

class ChatMessageController extends Controller
{
    public function __construct(
        private readonly ChatRoomRepositoryInterface $chatRoomRepository,
        private readonly ChatMessageRepositoryInterface $chatMessageRepository
    ) {}

    public function index(ChatRoom $chatRoom): Collection
    {
        return $this->chatMessageRepository->list([
            'chat_room_id' => $chatRoom->id,
        ]);
    }

    public function store(MessageStoreRequest $request, int $roomId): CreatedMessageResource
    {
        $validatedRequest = $request->validated();

        [$chatRoom, $newRoom] = $this->chatRoomRepository
            ->findOrCreate($roomId, $validatedRequest['friend_id'] ?? null);

        $message = $this->chatMessageRepository->create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => auth()->user()->id,
            'text' => $validatedRequest['text'],
        ]);

        broadcast(new MessageSent($message));

        if ($newRoom) {
            $chatRoom->loadUsersAndLastMessage();
            broadcast(new ChatCreated($validatedRequest['friend_id'], $chatRoom));
        }

        return new CreatedMessageResource($message, $newRoom);
    }
}
