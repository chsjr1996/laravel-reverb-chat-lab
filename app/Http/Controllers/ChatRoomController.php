<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Interfaces\ChatRoomRepositoryInterface;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;

class ChatRoomController extends Controller
{
    public function __construct(private ChatRoomRepositoryInterface $chatRoomRepository) {}

    public function store()
    {
        //
    }

    public function index(): InertiaResponse|ResponseFactory
    {
        return inertia('Chat', [
            'rooms' => $this->chatRoomRepository->listWhereHasCurrentUser(),
        ]);
    }

    public function show(ChatRoom $chatRoom): InertiaResponse|ResponseFactory
    {
        $chatRoom->load('users');

        return inertia('Chat', [
            'rooms' => $this->chatRoomRepository->listWhereHasCurrentUser(),
            'room' => $chatRoom,
        ]);
    }

    public function edit(User $user): InertiaResponse|Redirector|ResponseFactory|RedirectResponse
    {
        $chatRoom = $this->chatRoomRepository->findChatRoomByUsers($user->id, true);

        if ($chatRoom) {
            return redirect(route('chat.room.show', $chatRoom));
        }

        return inertia('Chat', [
            'rooms' => $this->chatRoomRepository->listWhereHasCurrentUser(),
            'room' => null,
            'user' => UserResource::make($user),
            'newRoom' => true,
        ]);
    }
}
