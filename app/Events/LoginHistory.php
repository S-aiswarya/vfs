<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoginHistory
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $login_data;
    public $user_guard;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($login_data, $user_guard)
    {
        $this->login_data = $login_data;
        $this->user_guard = $user_guard;
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
