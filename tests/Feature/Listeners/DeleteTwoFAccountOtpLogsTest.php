<?php

namespace Tests\Feature\Listeners;

use App\Events\TwoFAccountDeleted;
use App\Listeners\DeleteTwoFAccountOtpLogs;
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
 * DeleteTwoFAccountOtpLogsTest test class
 */
#[CoversClass(DeleteTwoFAccountOtpLogs::class)]
class DeleteTwoFAccountOtpLogsTest extends FeatureTestCase
{
    #[Test]
    public function test_it_deletes_from_otplogs()
    {
        $owner       = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();

        $this->actingAs($owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/otp')
            ->assertOk();

        $this->assertDatabaseHas('otp_logs', [
            'owner_id' => $owner->id,
            'requester_id' => $owner->id,
            'twofaccount_id' => $twofaccount->id,
        ]);

        $twofaccount->delete();

        $this->assertDatabaseMissing('otp_logs', [
            'owner_id' => $owner->id,
            'requester_id' => $owner->id,
            'twofaccount_id' => $twofaccount->id,
        ]);
    }

    #[Test]
    public function test_it_deletes_from_otplogs_for_given_twofaccount_only()
    {
        $owner       = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();
        $anotherTwoFAccount = TwoFAccount::factory()->for($owner)->create();

        $this->actingAs($owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $twofaccount->id . '/otp')
            ->assertOk();

        $this->actingAs($owner, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts/' . $anotherTwoFAccount->id . '/otp')
            ->assertOk();

        $this->assertDatabaseHas('otp_logs', [
            'owner_id' => $owner->id,
            'requester_id' => $owner->id,
            'twofaccount_id' => $twofaccount->id,
        ]);

        $twofaccount->delete();

        $this->assertDatabaseMissing('otp_logs', [
            'owner_id' => $owner->id,
            'requester_id' => $owner->id,
            'twofaccount_id' => $twofaccount->id,
        ]);
        
        $this->assertDatabaseHas('otp_logs', [
            'owner_id' => $owner->id,
            'requester_id' => $owner->id,
            'twofaccount_id' => $anotherTwoFAccount->id,
        ]);
    }

    #[Test]
    public function test_it_listens_to_TwoFAccountDeleted_event()
    {
        Event::fake();

        Event::assertListening(
            TwoFAccountDeleted::class,
            DeleteTwoFAccountOtpLogs::class
        );
    }
}
