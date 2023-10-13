<?php

namespace Elfcms\Elfcms\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SomeMailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventCode,
            $eventProps,
            $eventUser;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($eventCode,$eventProps,$eventUser = null)
    {
        $this->eventCode = $eventCode;
        $this->eventProps = $eventProps;
        $this->eventUser = $eventUser;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
