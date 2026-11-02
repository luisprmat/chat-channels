<?php

use App\Models\Channel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('channels.{channel}', function ($user, Channel $channel): bool {
    return $channel->isSubscribed($user);
});

Broadcast::channel('workspace', function ($user): array {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
