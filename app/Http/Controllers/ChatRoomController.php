<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;

class ChatRoomController extends Controller
{
    public function index(): InertiaResponse|ResponseFactory
    {
        return inertia('Chat/Rooms');
    }

    public function show(ChatRoom $chatRoom): InertiaResponse|ResponseFactory
    {
        $chatRoom->load('users');

        return inertia('Chat', [
            'room' => $chatRoom,
        ]);
    }
}
