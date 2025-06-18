<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @noinspection PhpVoidFunctionResultUsedInspection
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     *
     * @todo Made another checks, like user is blocked on room? banned? etc...
     */
    public function canJoinRoom(int $roomId): bool
    {
        return ChatRoomUser::userInRoom($this->id, $roomId)->exists();
    }

    #[Scope]
    public function excludeLoggedInUser(Builder $query): void
    {
        $query->where('id', '!=', auth()->user()->id);
    }

    #[Scope]
    public function excludeFriends(Builder $query): void
    {
        $loggedInUserId = auth()->id();

        $chatRoomIds = ChatRoomUser::where('user_id', $loggedInUserId)
            ->pluck('chat_room_id');

        $query->whereNotIn('id', function ($subQuery) use ($chatRoomIds, $loggedInUserId) {
            $subQuery->select('user_id')
                ->from('chat_room_users')
                ->join('chat_rooms', 'chat_rooms.id', '=', 'chat_room_users.chat_room_id')
                ->whereIn('chat_room_id', $chatRoomIds)
                ->where('user_id', '!=', $loggedInUserId)
                ->where('chat_rooms.is_group', false);
        });
    }
}
