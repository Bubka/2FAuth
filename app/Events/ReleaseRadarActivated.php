<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReleaseRadarActivated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\Group
     */
    // public $group;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Group  $group
     * @return void
     */
    public function __construct()
    {
        // $this->group = $group;
    }
}
