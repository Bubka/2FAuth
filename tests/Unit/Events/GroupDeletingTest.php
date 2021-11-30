<?php

namespace Tests\Unit\Events;

use App\Group;
use App\Events\GroupDeleting;
use Tests\TestCase;


/**
 * @covers \App\Events\GroupDeleting
 */
class GroupDeletingTest extends TestCase
{
    /**
     * @test
     */
    public function test_event_constructor()
    {
        $group = factory(Group::class)->make();
        $event = new GroupDeleting($group);

        $this->assertSame($group, $event->group);
    }
}