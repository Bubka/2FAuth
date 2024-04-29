<?php

namespace Tests\Unit\Listeners;

use App\Events\GroupDeleting;
use App\Listeners\DissociateTwofaccountFromGroup;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

/**
 * DissociateTwofaccountFromGroupTest test class
 */
#[CoversClass(DissociateTwofaccountFromGroup::class)]
class DissociateTwofaccountFromGroupTest extends TestCase
{
    /**
     * @test
     */
    public function test_DissociateTwofaccountFromGroup_listen_to_groupDeleting_event()
    {
        Event::fake();

        Event::assertListening(
            GroupDeleting::class,
            DissociateTwofaccountFromGroup::class
        );
    }
}
