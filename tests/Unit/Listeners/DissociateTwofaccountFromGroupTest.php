<?php

namespace Tests\Unit\Listeners;

use App\Models\Group;
use App\Events\GroupDeleting;
use Tests\TestCase;
use App\Listeners\DissociateTwofaccountFromGroup;
use Illuminate\Support\Facades\Event;


/**
 * @covers \App\Listeners\DissociateTwofaccountFromGroup
 */
class DissociateTwofaccountFromGroupTest extends TestCase
{
    // public function test_twofaccount_is_released_on_group_deletion()
    // {
    //     $group = Group::factory()->make();
    //     $event = new GroupDeleting($group);
    //     $listener = new DissociateTwofaccountFromGroup();

    //     $this->assertNull($listener->handle($event));
    // }


    public function test_DissociateTwofaccountFromGroup_listen_to_groupDeleting_event()
    {
        Event::fake();

        Event::assertListening(
            GroupDeleting::class,
            DissociateTwofaccountFromGroup::class
        );
    }
}