<?php

use App\Broadcasting\ChatPresenceChannel;
use App\Broadcasting\ChatRoomChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.room.{roomId}', ChatRoomChannel::class);
Broadcast::channel('chat', ChatPresenceChannel::class);
