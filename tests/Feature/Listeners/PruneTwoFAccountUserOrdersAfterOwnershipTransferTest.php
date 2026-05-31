<?php

namespace Tests\Feature\Listeners;

use App\Events\TwoFAccountOwnershipTransferred;
use App\Facades\TwoFAccounts;
use App\Listeners\PruneTwoFAccountUserOrdersAfterOwnershipTransfer;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * PruneTwoFAccountUserOrdersAfterOwnershipTransferTest test class
 */
#[CoversClass(PruneTwoFAccountUserOrdersAfterOwnershipTransfer::class)]
class PruneTwoFAccountUserOrdersAfterOwnershipTransferTest extends FeatureTestCase
{
    #[Test]
    public function test_it_triggers_user_pruning()
    {
        TwoFAccounts::partialMock()->shouldReceive('pruneUsersWithoutAccessForAccount')->once();

        $owner = User::factory()->create();
        $newOwner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();

        TwoFAccounts::transferOwnership($twofaccount, $newOwner);
    }

    #[Test]
    public function test_it_listens_to_TwoFAccountOwnershipTransferred_event()
    {
        Event::fake();

        Event::assertListening(
            TwoFAccountOwnershipTransferred::class,
            PruneTwoFAccountUserOrdersAfterOwnershipTransfer::class
        );
    }
}
