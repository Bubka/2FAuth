<?php

namespace App\Events;

use App\Models\Group;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GroupDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\Group
     */
    public $group;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Group $group)
    {
        $this->group = $group;

        Log::info(sprintf('Group %s (id #%d) deleted ', var_export($group->name, true), $group->id));
    }
}
