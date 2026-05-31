<?php

namespace Tests\Feature\Listeners;

use App\Events\TwoFAccountDeleted;
use App\Listeners\DeleteTwoFAccountUserOrders;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountUserOrder;
use App\Models\User;
use App\Services\TwoFAccountShareService;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * DeleteTwoFAccountUserOrdersTest test class
 */
#[CoversClass(DeleteTwoFAccountUserOrders::class)]
class DeleteTwoFAccountUserOrdersTest extends FeatureTestCase
{
    #[Test]
    public function test_it_deletes_from_users_custom_order()
    {
        $owner       = User::factory()->create();
        $targetUserA = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();
        $service     = new TwoFAccountShareService;

        $service->shareWithUser($twofaccount, $owner, $targetUserA);

        TwoFAccountUserOrder::create([
            'user_id' => $owner->id,
            'twofaccount_id' => $twofaccount->id,
            'position' => 1,
        ]);
        TwoFAccountUserOrder::create([
            'user_id' => $targetUserA->id,
            'twofaccount_id' => $twofaccount->id,
            'position' => 1,
        ]);

        $twofaccount->delete();

        $this->assertDatabaseMissing('twofaccount_user_orders', [
            'user_id' => $owner->id,
            'twofaccount_id' => $twofaccount->id,
            'position' => 1,
        ]);
        $this->assertDatabaseMissing('twofaccount_user_orders', [
            'user_id' => $targetUserA->id,
            'twofaccount_id' => $twofaccount->id,
            'position' => 1,
        ]);
    }

    #[Test]
    public function test_it_listens_to_TwoFAccountDeleted_event()
    {
        Event::fake();

        Event::assertListening(
            TwoFAccountDeleted::class,
            DeleteTwoFAccountUserOrders::class
        );
    }
}
