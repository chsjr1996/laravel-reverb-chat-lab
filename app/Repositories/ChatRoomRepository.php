<?php

namespace App\Repositories;

use App\Interfaces\ChatRoomRepositoryInterface;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ChatRoomRepository extends BaseRepository implements ChatRoomRepositoryInterface
{
    protected string $model = ChatRoom::class;

    public function createGroupChatRoom(array $data): ChatRoom
    {
        return DB::transaction(function () use ($data) {
            $chatRoom = ChatRoom::create([
                'name' => $data['name'],
                'is_group' => true,
            ]);

            $chatRoomUsers = [];
            $chatRoomUsers[] = [
                'chat_room_id' => $chatRoom->id,
                'user_id' => auth()->user()->id,
                'is_admin' => true,
            ];

            foreach ($data['users'] as $user) {
                $chatRoomUsers[] = [
                    'chat_room_id' => $chatRoom->id,
                    'user_id' => $user,
                    'is_admin' => false,
                ];
            }

            ChatRoomUser::insert($chatRoomUsers);

            return $chatRoom;
        });
    }

    public function listWhereHasCurrentUser()
    {
        return $this->resolvedModel->newQuery()
            ->whereHasCurrentUser()
            ->withLastMessage()
            ->with('users')
            ->orderByDesc(function ($query) {
                $query->select('created_at')
                    ->from('chat_messages')
                    ->whereColumn('chat_room_id', 'chat_rooms.id')
                    ->latest()
                    ->limit(1);
            })
            ->get();
    }

    public function findChatRoomByUsers(int $userId, bool $private = false)
    {
        return $this->resolvedModel->newQuery()
            ->whereHasCurrentUser()
            ->whereHasUser($userId)
            ->when($private, fn ($q) => $q->where('is_group', false))
            ->with('users')
            ->first();
    }

    /**
     * Return an array containing a chat room and a boolean indicating whether it was created.
     */
    public function findOrCreate(int $id, ?int $friendId = null): array
    {
        if ($id) {
            return [$this->find($id), false];
        }

        if (! $friendId) {
            throw new InvalidArgumentException('Friend ID is required to create a chat room.');
        }

        $chatRoom = ChatRoom::create();
        ChatRoomUser::insert([
            ['chat_room_id' => $chatRoom->id, 'user_id' => auth()->user()->id],
            ['chat_room_id' => $chatRoom->id, 'user_id' => $friendId],
        ]);

        return [$chatRoom, true];
    }
}
