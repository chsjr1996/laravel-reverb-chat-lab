<?php

use App\Broadcasting\ChatPresenceChannel;
use App\Broadcasting\ChatRoomChannel;
use App\Broadcasting\ChatRoomObserverChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.room.observer.{userId}', ChatRoomObserverChannel::class);
Broadcast::channel('chat.room.{roomId}', ChatRoomChannel::class);
Broadcast::channel('chat', ChatPresenceChannel::class);
