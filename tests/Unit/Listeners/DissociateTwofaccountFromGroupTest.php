<?php

namespace Tests\Unit\Listeners;

use App\Events\GroupDeleting;
use App\Listeners\DissociateTwofaccountFromGroup;
use App\Models\Group;
use App\Models\TwoFAccount;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
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
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
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
