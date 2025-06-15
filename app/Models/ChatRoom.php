<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class ChatRoom
 *
 * @property int $id
 * @property string $name
 * @property bool $is_group
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class ChatRoom extends Model
{
    /** @use HasFactory<\Database\Factories\ChatRoomFactory> */
    use HasFactory, SoftDeletes;

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            ChatRoomUser::class,
            'chat_room_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_room_id', 'id');
    }

    #[Scope]
    protected function withCurrentUser(Builder $query): void
    {
        $query->whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })->with('users');
    }

    #[Scope]
    protected function withLastMessage(Builder $query): void
    {
        $query->with(['messages' => function ($query) {
            $query->latest()->take(1);
        }]);
    }
}
