<?php

namespace Tests\Unit\Events;

use App\Events\GroupDeleted;
use App\Models\Group;
use Tests\TestCase;

/**
 * @covers \App\Events\GroupDeleted
 */
class GroupDeletedTest extends TestCase
{
    /**
     * @test
     */
    public function test_event_constructor()
    {
        $group = Group::factory()->make();
        $event = new GroupDeleted($group);

        $this->assertSame($group, $event->group);
    }
}
