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
    public function test_it_stores_time_to_session()
    {
        $group = Group::factory()->make();
        $event = new GroupDeleting($group);
        $listener = new DissociateTwofaccountFromGroup();

        $this->assertNull($listener->handle($event));
    }
}