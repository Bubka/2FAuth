<?php

namespace Tests\Feature\Listeners;

use App\Events\GroupDeleted;
use App\Listeners\DissociateTwofaccountFromGroup;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * DissociateTwofaccountFromGroupTest test class
 */
#[CoversClass(DissociateTwofaccountFromGroup::class)]
class DissociateTwofaccountFromGroupTest extends FeatureTestCase
{
    #[Test]
    public function test_it_listens_to_groupDeleted_event()
    {
        Event::fake();

        Event::assertListening(
            GroupDeleted::class,
            DissociateTwofaccountFromGroup::class
        );
    }

    #[Test]
    public function test_handle_deletes_group_assignments_for_deleted_group()
    {
        $user = User::factory()->create();
        $group = Group::factory()->for($user)->create();
        $twofaccount = $this->createTwofaccountInGroup($user, $group);

        $this->assertDatabaseHas('twofaccount_group_assignments', [
            'twofaccount_id' => $twofaccount->id,
            'user_id'        => $user->id,
            'group_id'       => $group->id,
        ]);

        (new DissociateTwofaccountFromGroup)->handle(new GroupDeleted($group));

        $this->assertDatabaseMissing('twofaccount_group_assignments', [
            'twofaccount_id' => $twofaccount->id,
            'user_id'        => $user->id,
            'group_id'       => $group->id,
        ]);
    }
}
