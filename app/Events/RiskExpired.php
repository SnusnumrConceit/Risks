<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
//use Illuminate\Broadcasting\Channel;
//use Illuminate\Broadcasting\PrivateChannel;
//use Illuminate\Broadcasting\PresenceChannel;
//use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RiskExpired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $beforeExpired;

    /**
     * Create a new event instance.
     *
     * @param int $beforeExpired
     * @return void
     */
    public function __construct(int $beforeExpired)
    {
        $this->beforeExpired = $beforeExpired;
    }
}
