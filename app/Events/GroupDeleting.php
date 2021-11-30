<?php

namespace App\Events;

use App\Group;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupDeleting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $group;

    /**
     * Create a new event instance.
     *
     * @param  \App\Group  $group
     * @return void
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }
}
