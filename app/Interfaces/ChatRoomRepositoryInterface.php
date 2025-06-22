<?php

namespace App\Interfaces;

use App\Models\ChatRoom;

interface ChatRoomRepositoryInterface extends BaseRepositoryInterface
{
    public function createGroupChatRoom(array $data): ChatRoom;

    public function listWhereHasCurrentUser();

    public function findChatRoomByUsers(int $userId, bool $private = false);

    public function findOrCreate(int $id, ?int $friendId = null);
}
