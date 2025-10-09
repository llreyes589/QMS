<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MakeQueue implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $que;

    public function __construct($que)
    {
        $this->que = $que;
    }

    // Broadcast via public channel
    public function broadcastOn()
    {
        return new Channel('public-queues');
    }

    public function broadcastAs()
    {
        return 'queue.stored';
    }
}
