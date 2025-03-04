<?php

namespace Tests\Unit\Listeners;

use App\Events\GroupDeleted;
use App\Listeners\DissociateTwofaccountFromGroup;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * DissociateTwofaccountFromGroupTest test class
 */
#[CoversClass(DissociateTwofaccountFromGroup::class)]
class DissociateTwofaccountFromGroupTest extends TestCase
{
    #[Test]
    public function test_DissociateTwofaccountFromGroup_listen_to_groupDeleted_event()
    {
        Event::fake();

        Event::assertListening(
            GroupDeleted::class,
            DissociateTwofaccountFromGroup::class
        );
    }
}
