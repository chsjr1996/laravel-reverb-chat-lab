<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChatRoomUser
 *
 * @property int $id
 * @property int $chat_room_id
 * @property int $user_id
 * @property bool $is_blocked
 * @property bool $is_admin
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ChatRoomUser extends Model
{
    #[Scope]
    protected function userInRoom(Builder $query, $userId, $roomId): void
    {
        $query->where('user_id', $userId)
            ->where('chat_room_id', $roomId);
    }
}
