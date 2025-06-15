<?php

namespace App\Broadcasting;

use App\Models\User;

class ChatRoomChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, int $roomId): array|bool
    {
        $key = "chat.room.{$roomId}.{$user->id}";
        $ttl = app()->isProduction() ? now()->addSeconds(60) : -1;

        return cache()->remember($key, $ttl, fn () => $user->canJoinRoom($roomId));
    }
}
