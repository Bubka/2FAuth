<?php

namespace Tests\Unit\Events;

use App\Events\GroupDeleted;
use App\Models\Group;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * GroupDeletedTest test class
 */
#[CoversClass(GroupDeleted::class)]
class GroupDeletedTest extends TestCase
{
    #[Test]
    public function test_event_constructor()
    {
        $group = Group::factory()->make();
        $event = new GroupDeleted($group);

        $this->assertSame($group, $event->group);
    }
}
