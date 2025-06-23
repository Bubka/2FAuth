<?php

namespace Tests\Feature;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\TestCase;

class TwoFAccountSharingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_shared_account_is_visible_to_all_users()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        // Create a shared account
        $sharedAccount = TwoFAccount::factory()->for($owner)->create([
            'is_shared' => true,
        ]);

        // Create a private account
        $privateAccount = TwoFAccount::factory()->for($owner)->create([
            'is_shared' => false,
        ]);

        // Owner should see both accounts
        $ownerAccounts = TwoFAccount::accessibleBy($owner)->get();
        $this->assertCount(2, $ownerAccounts);
        $this->assertTrue($ownerAccounts->contains($sharedAccount));
        $this->assertTrue($ownerAccounts->contains($privateAccount));

        // Other user should only see the shared account
        $otherUserAccounts = TwoFAccount::accessibleBy($otherUser)->get();
        $this->assertCount(1, $otherUserAccounts);
        $this->assertTrue($otherUserAccounts->contains($sharedAccount));
        $this->assertFalse($otherUserAccounts->contains($privateAccount));
    }

    #[Test]
    public function test_create_shared_account_via_api()
    {
        $user = User::factory()->create();

        $data = array_merge(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP, [
            'is_shared' => true,
        ]);

        $response = $this->actingAs($user, 'api-guard')
            ->json('POST', '/api/v1/twofaccounts', $data)
            ->assertStatus(201)
            ->assertJsonFragment(['is_shared' => true]);

        $account = TwoFAccount::find($response->json('id'));
        $this->assertTrue($account->is_shared);
    }

    #[Test]
    public function test_update_account_to_shared_via_api()
    {
        $user = User::factory()->create();
        $account = TwoFAccount::factory()->for($user)->create([
            'is_shared' => false,
        ]);

        $data = [
            'service' => $account->service,
            'account' => $account->account,
            'icon' => $account->icon,
            'otp_type' => $account->otp_type,
            'secret' => $account->secret,
            'digits' => $account->digits,
            'algorithm' => $account->algorithm,
            'period' => $account->period,
            'is_shared' => true,
        ];

        $this->actingAs($user, 'api-guard')
            ->json('PUT', '/api/v1/twofaccounts/' . $account->id, $data)
            ->assertOk()
            ->assertJsonFragment(['is_shared' => true]);

        $account->refresh();
        $this->assertTrue($account->is_shared);
    }
}
