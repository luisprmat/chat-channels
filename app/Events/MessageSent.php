<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        $message->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|Channel[]|string[]|string
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('channels.'.$this->message->channel_id);
    }
}
