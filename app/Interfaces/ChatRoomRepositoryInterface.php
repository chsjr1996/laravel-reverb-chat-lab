<?php

namespace App\Interfaces;

interface ChatRoomRepositoryInterface extends BaseRepositoryInterface
{
    public function listWhereHasCurrentUser();

    public function findChatRoomByUsers(int $userId, bool $private = false);

    public function findOrCreate(int $id, ?int $friendId = null);
}
