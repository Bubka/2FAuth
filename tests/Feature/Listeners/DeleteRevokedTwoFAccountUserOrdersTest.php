<?php

namespace Tests\Feature\Listeners;

use App\Events\TwoFAccountShareRevoked;
use App\Listeners\DeleteRevokedTwoFAccountUserOrders;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountUserOrder;
use App\Models\User;
use App\Services\TwoFAccountShareService;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * DeleteRevokedTwoFAccountUserOrdersTest test class
 */
#[CoversClass(DeleteRevokedTwoFAccountUserOrders::class)]
class DeleteRevokedTwoFAccountUserOrdersTest extends FeatureTestCase
{
    #[Test]
    public function test_it_deletes_users_custom_order()
    {
        $owner       = User::factory()->create();
        $targetUserA = User::factory()->create();
        $targetUserB = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();
        $service     = new TwoFAccountShareService;

        $service->shareWithUser($twofaccount, $owner, $targetUserA);
        $service->shareWithUser($twofaccount, $owner, $targetUserB);

        TwoFAccountUserOrder::create([
            'user_id' => $targetUserA->id,
            'twofaccount_id' => $twofaccount->id,
            'position' => 1,
        ]);
        TwoFAccountUserOrder::create([
            'user_id' => $targetUserB->id,
            'twofaccount_id' => $twofaccount->id,
            'position' => 1,
        ]);

        $service->revokeUserShare($twofaccount, $targetUserA);

        $this->assertDatabaseMissing('twofaccount_user_orders', [
            'user_id' => $targetUserA->id,
            'twofaccount_id' => $twofaccount->id,
            'position' => 1,
        ]);
        $this->assertDatabaseHas('twofaccount_user_orders', [
            'user_id' => $targetUserB->id,
            'twofaccount_id' => $twofaccount->id,
            'position' => 1,
        ]);
    }

    #[Test]
    public function test_it_listens_to_TwoFAccountShareRevoked_event()
    {
        Event::fake();

        Event::assertListening(
            TwoFAccountShareRevoked::class,
            DeleteRevokedTwoFAccountUserOrders::class
        );
    }
}
