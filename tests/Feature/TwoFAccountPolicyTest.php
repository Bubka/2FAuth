<?php

namespace Tests\Feature;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

class TwoFAccountPolicyTest extends FeatureTestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Models\User
     */
    protected $creator;

    /**
     * @var \App\Models\User
     */
    protected $otherUser;

    /**
     * @var \App\Models\TwoFAccount
     */
    protected $sharedAccount;

    /**
     * @var \App\Models\TwoFAccount
     */
    protected $privateAccount;

    public function setUp(): void
    {
        parent::setUp();

        $this->creator = User::factory()->create();
        $this->otherUser = User::factory()->create();

        $this->sharedAccount = TwoFAccount::factory()->for($this->creator)->create([
            'is_shared' => true,
        ]);

        $this->privateAccount = TwoFAccount::factory()->for($this->creator)->create([
            'is_shared' => false,
        ]);
    }

    /** @test */
    public function creator_can_view_own_shared_account()
    {
        $response = $this->actingAs($this->creator, 'api-guard')
                         ->getJson("/api/v1/twofaccounts/{$this->sharedAccount->id}");

        $response->assertOk();
    }

    /** @test */
    public function other_user_can_view_shared_account()
    {
        $response = $this->actingAs($this->otherUser, 'api-guard')
                         ->getJson("/api/v1/twofaccounts/{$this->sharedAccount->id}");

        $response->assertOk();
    }

    /** @test */
    public function other_user_cannot_view_private_account()
    {
        $response = $this->actingAs($this->otherUser, 'api-guard')
                         ->getJson("/api/v1/twofaccounts/{$this->privateAccount->id}");

        $response->assertForbidden();
    }

    /** @test */
    public function creator_can_update_own_shared_account()
    {
        $updateData = array_merge(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP_NO_ICON, [
            'service' => 'Updated Service',
            'account' => 'updated@example.com',
        ]);

        $response = $this->actingAs($this->creator, 'api-guard')
                         ->putJson("/api/v1/twofaccounts/{$this->sharedAccount->id}", $updateData);

        $response->assertOk();
    }

    /** @test */
    public function other_user_cannot_update_shared_account()
    {
        $updateData = array_merge(OtpTestData::ARRAY_OF_FULL_VALID_PARAMETERS_FOR_CUSTOM_TOTP_NO_ICON, [
            'service' => 'Updated Service',
            'account' => 'updated@example.com',
        ]);

        $response = $this->actingAs($this->otherUser, 'api-guard')
                         ->putJson("/api/v1/twofaccounts/{$this->sharedAccount->id}", $updateData);

        $response->assertForbidden();
    }

    /** @test */
    public function creator_can_delete_own_shared_account()
    {
        // Create a fresh shared account for this test
        $accountToDelete = TwoFAccount::factory()->for($this->creator)->create([
            'is_shared' => true,
        ]);

        $response = $this->actingAs($this->creator, 'api-guard')
                         ->deleteJson("/api/v1/twofaccounts/{$accountToDelete->id}");

        $response->assertStatus(204);
    }

    /** @test */
    public function other_user_cannot_delete_shared_account()
    {
        $response = $this->actingAs($this->otherUser, 'api-guard')
                         ->deleteJson("/api/v1/twofaccounts/{$this->sharedAccount->id}");

        $response->assertForbidden();
    }

    /** @test */
    public function shared_accounts_appear_in_other_users_index()
    {
        // Test the API endpoint
        $response = $this->actingAs($this->otherUser, 'api-guard')
                         ->getJson('/api/v1/twofaccounts');

        $response->assertOk();
        
        // The response structure is an array directly, not wrapped in 'data'
        $accounts = $response->json();
        
        // Should have at least one shared account
        $this->assertGreaterThan(0, count($accounts), 'Other user should see at least one shared account');
        
        // Check that the shared account from setUp is included
        $sharedAccountIds = collect($accounts)->pluck('id')->toArray();
        $this->assertContains($this->sharedAccount->id, $sharedAccountIds, 'Should contain the shared account from setUp');
        $this->assertNotContains($this->privateAccount->id, $sharedAccountIds, 'Should not contain the private account');
    }

    /** @test */
    public function policy_allows_access_to_shared_account()
    {
        $this->assertTrue($this->otherUser->can('view', $this->sharedAccount));
        $this->assertFalse($this->otherUser->can('update', $this->sharedAccount));
        $this->assertFalse($this->otherUser->can('delete', $this->sharedAccount));
    }

    /** @test */
    public function policy_allows_creator_full_access_to_shared_account()
    {
        $this->assertTrue($this->creator->can('view', $this->sharedAccount));
        $this->assertTrue($this->creator->can('update', $this->sharedAccount));
        $this->assertTrue($this->creator->can('delete', $this->sharedAccount));
    }

    /** @test */
    public function policy_denies_access_to_private_account()
    {
        $this->assertFalse($this->otherUser->can('view', $this->privateAccount));
        $this->assertFalse($this->otherUser->can('update', $this->privateAccount));
        $this->assertFalse($this->otherUser->can('delete', $this->privateAccount));
    }
}
