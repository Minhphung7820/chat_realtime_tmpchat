<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat', function ($user) {
    return $user->load(['friends', 'conversations', 'conversations.messages']);
});
Broadcast::channel('typing.{conversation}', function ($user, $conversation) {
    return true;
});
Broadcast::channel('stopTyping.{conversation}', function ($user, $conversation) {
    return true;
});
Broadcast::channel('send.{conversation}', function ($user, $conversation) {
    return true;
});
Broadcast::channel('seen.{conversation}', function ($data) {
    Log::info("Đã xem",["conv"=>$data['seenerName']." Đã xem"]);
    return true;
});
