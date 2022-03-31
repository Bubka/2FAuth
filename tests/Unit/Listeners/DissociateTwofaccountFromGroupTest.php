<?php

namespace Tests\Unit\Listeners;

use App\Models\Group;
use App\Events\GroupDeleting;
use Tests\FeatureTestCase;
use App\Listeners\DissociateTwofaccountFromGroup;


/**
 * @covers \App\Listeners\DissociateTwofaccountFromGroup
 */
class DissociateTwofaccountFromGroupTest extends FeatureTestCase
{
    public function test_twofaccount_is_released_on_group_deletion()
    {
        $group = Group::factory()->make();
        $event = new GroupDeleting($group);
        $listener = new DissociateTwofaccountFromGroup();

        $this->assertNull($listener->handle($event));
    }
}