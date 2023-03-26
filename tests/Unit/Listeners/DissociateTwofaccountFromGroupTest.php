<?php

namespace Tests\Unit\Listeners;

use App\Events\GroupDeleting;
use App\Listeners\DissociateTwofaccountFromGroup;
use App\Models\Group;
use App\Models\TwoFAccount;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @covers \App\Listeners\DissociateTwofaccountFromGroup
 */
class DissociateTwofaccountFromGroupTest extends TestCase
{
    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function test_twofaccount_is_released_on_group_deletion()
    {
        $this->mock('alias:' . TwoFAccount::class, function (MockInterface $twoFAccount) {
            $twoFAccount->shouldReceive('where->update')
                ->once()
                ->andReturn(1);
        });

        $group    = Group::factory()->make();
        $event    = new GroupDeleting($group);
        $listener = new DissociateTwofaccountFromGroup();

        $this->assertNull($listener->handle($event));
    }

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
