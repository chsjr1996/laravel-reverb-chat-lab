<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;

class ChatRoomController extends Controller
{
    /**
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     */
    public function index(): InertiaResponse|ResponseFactory
    {
        $chatRooms = ChatRoom::withCurrentUser()->withLastMessage()->get();

        return inertia('Chat', [
            'rooms' => $chatRooms,
        ]);
    }

    /**
     * @noinspection PhpDynamicAsStaticMethodCallInspection
     */
    public function show(ChatRoom $chatRoom): InertiaResponse|ResponseFactory
    {
        $chatRooms = ChatRoom::withCurrentUser()->withLastMessage()->get();
        $chatRoom->load('users');

        return inertia('Chat', [
            'rooms' => $chatRooms,
            'room' => $chatRoom,
        ]);
    }
}
