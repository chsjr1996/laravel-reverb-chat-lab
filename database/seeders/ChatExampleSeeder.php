<?php

namespace Database\Seeders;

use App\Models\ChatRoom;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatExampleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        [$user1, $user2, $user3, $user4] = $this->createChatUsers();
        [$chatRoom1, $chatRoom2, $chatRoom3] = $this->createChatRooms();
        $this->bindUsersToChatRooms($user1, $user2, $user3, $user4, $chatRoom1, $chatRoom2, $chatRoom3);
        $this->createChatMessages($user1, $user2, $user3, $user4, $chatRoom1, $chatRoom2, $chatRoom3);
    }

    private function createChatUsers(): array
    {
        $user1 = User::factory()->create([
            'name' => 'User 1',
            'email' => 'user1@example.com',
        ]);
        $user2 = User::factory()->create([
            'name' => 'User 2',
            'email' => 'user2@example.com',
        ]);
        $user3 = User::factory()->create([
            'name' => 'User 3',
            'email' => 'user3@example.com',
        ]);
        $user4 = User::factory()->create([
            'name' => 'User 4',
            'email' => 'user4@example.com',
        ]);

        return [$user1, $user2, $user3, $user4];
    }

    private function createChatRooms(): array
    {
        $chatRoom1 = ChatRoom::factory()->create();
        $chatRoom2 = ChatRoom::factory()->create(['is_group' => true, 'name' => 'Family group']);
        $chatRoom3 = ChatRoom::factory()->create();

        return [$chatRoom1, $chatRoom2, $chatRoom3];
    }

    private function bindUsersToChatRooms($user1, $user2, $user3, $user4, $chatRoom1, $chatRoom2, $chatRoom3): void
    {
        DB::table('chat_room_users')->insert([
            [
                'chat_room_id' => $chatRoom1->id,
                'user_id' => $user1->id,
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom1->id,
                'user_id' => $user2->id,
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom2->id,
                'user_id' => $user1->id,
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom2->id,
                'user_id' => $user2->id,
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom2->id,
                'user_id' => $user3->id,
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom3->id,
                'user_id' => $user1->id,
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom3->id,
                'user_id' => $user4->id,
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    private function createChatMessages($user1, $user2, $user3, $user4, $chatRoom1, $chatRoom2, $chatRoom3): void
    {
        DB::table('chat_messages')->insert([
            [
                'chat_room_id' => $chatRoom1->id,
                'user_id' => $user1->id,
                'text' => 'Hello, how are you?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom1->id,
                'user_id' => $user2->id,
                'text' => 'I\'m fine, thanks! And you?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom3->id,
                'user_id' => $user4->id,
                'text' => 'Hey, how are you?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom2->id,
                'user_id' => $user1->id,
                'text' => 'Hello family!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom2->id,
                'user_id' => $user2->id,
                'text' => 'This week is going to be great!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom2->id,
                'user_id' => $user3->id,
                'text' => 'Yes, great!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
