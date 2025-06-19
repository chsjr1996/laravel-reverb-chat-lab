<?php

namespace App\Broadcasting;

use App\Models\User;

class ChatRoomObserverChannel
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
    public function join(User $user, int $userId): array|bool
    {
        return (int) $user->id === (int) $userId;
    }
}
