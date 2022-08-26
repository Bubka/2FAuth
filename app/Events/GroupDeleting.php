<?php

namespace App\Events;

use App\Models\Group;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupDeleting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\Group
     */
    public $group;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Group  $group
     * @return void
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }
}
