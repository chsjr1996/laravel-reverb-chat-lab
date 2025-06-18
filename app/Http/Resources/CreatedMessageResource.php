<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreatedMessageResource extends JsonResource
{
    public function __construct($resource, private readonly bool $newRoom = false)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'user_id' => $this->user_id,
            'chat_room_id' => $this->chat_room_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'new_chat' => $this->newRoom,
        ];
    }
}
