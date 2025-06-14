<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

Broadcast::channel('chat.room.{roomId}', function ($user, $roomId) {
    $ttl = app()->isProduction() ? now()->addSeconds(60) : -1;

    return cache()->remember('chat.room.{roomId}', $ttl, function () use ($user, $roomId) {
        return DB::table('chat_room_users')
            ->where('chat_room_id', $roomId)
            ->where('user_id', $user->id)
            ->exists();
    });
});
