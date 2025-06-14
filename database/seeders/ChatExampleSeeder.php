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
        $user1 = User::factory()->create([
            'name' => 'User 1',
            'email' => 'user1@example.com',
        ]);
        $user2 = User::factory()->create([
            'name' => 'User 2',
            'email' => 'user2@example.com',
        ]);

        $chatRoom = ChatRoom::factory()->create();

        DB::table('chat_room_users')->insert([
            [
                'chat_room_id' => $chatRoom->id,
                'user_id' => $user1->id,
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chat_room_id' => $chatRoom->id,
                'user_id' => $user2->id,
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
