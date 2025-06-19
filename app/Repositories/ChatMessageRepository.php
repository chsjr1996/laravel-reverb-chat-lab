<?php

namespace App\Repositories;

use App\Interfaces\ChatMessageRepositoryInterface;
use App\Models\ChatMessage;
use Illuminate\Support\Collection;

class ChatMessageRepository extends BaseRepository implements ChatMessageRepositoryInterface
{
    protected string $model = ChatMessage::class;

    public function create(array $data = []): mixed
    {
        return ChatMessage::create([
            'chat_room_id' => $data['chat_room_id'],
            'user_id' => $data['user_id'],
            'text' => $data['text'],
        ]);
    }

    public function list(array $filters = []): Collection
    {
        return ChatMessage::query()
            ->when($filters['chat_room_id'] ?? null, fn ($q) => $q->filterByChatRoomId($filters['chat_room_id']))
            ->with('user:id,name')
            ->orderBy('id')
            ->get();
    }
}
