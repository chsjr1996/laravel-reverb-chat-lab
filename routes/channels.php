<?php

use Illuminate\Support\Facades\Broadcast;

// TODO: missing auth here! (typing bug?!)
Broadcast::channel('chat.{id}', function ($user, $id) {
    return true;
});
