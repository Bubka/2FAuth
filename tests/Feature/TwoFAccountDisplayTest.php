<?php

namespace Tests\Feature;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class TwoFAccountDisplayTest extends FeatureTestCase
{
    use RefreshDatabase;

    private User $user;
    private User $anotherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->anotherUser = User::factory()->create();
    }

    #[Test]
    public function test_shared_account_returns_is_shared_true_in_api_response()
    {
        $sharedAccount = TwoFAccount::factory()->for($this->user)->create([
            'service' => 'Test Service',
            'account' => 'test@example.com',
            'is_shared' => true
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->getJson('/api/v1/twofaccounts')
            ->assertStatus(200);

        $accountData = collect($response->json())
            ->firstWhere('id', $sharedAccount->id);

        $this->assertNotNull($accountData, 'Account not found in response');
        $this->assertArrayHasKey('is_shared', $accountData, 'is_shared field missing');
        $this->assertTrue($accountData['is_shared']);
    }

    #[Test]
    public function test_non_shared_account_returns_is_shared_false_in_api_response()
    {
        $privateAccount = TwoFAccount::factory()->for($this->user)->create([
            'service' => 'Private Service',
            'account' => 'private@example.com',
            'is_shared' => false
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->getJson('/api/v1/twofaccounts')
            ->assertStatus(200);

        $accountData = collect($response->json())
            ->firstWhere('id', $privateAccount->id);

        $this->assertFalse($accountData['is_shared']);
    }

    #[Test]
    public function test_another_user_can_see_shared_account_in_api()
    {
        $sharedAccount = TwoFAccount::factory()->for($this->user)->create([
            'service' => 'Shared Service',
            'account' => 'shared@example.com',
            'is_shared' => true
        ]);

        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->getJson('/api/v1/twofaccounts')
            ->assertStatus(200);

        $accountData = collect($response->json())
            ->firstWhere('id', $sharedAccount->id);

        $this->assertNotNull($accountData);
        $this->assertTrue($accountData['is_shared']);
        $this->assertEquals('Shared Service', $accountData['service']);
    }

    #[Test]
    public function test_another_user_cannot_see_private_account_in_api()
    {
        $privateAccount = TwoFAccount::factory()->for($this->user)->create([
            'service' => 'Private Service',
            'account' => 'private@example.com',
            'is_shared' => false
        ]);

        $response = $this->actingAs($this->anotherUser, 'api-guard')
            ->getJson('/api/v1/twofaccounts')
            ->assertStatus(200);

        $accountData = collect($response->json())
            ->firstWhere('id', $privateAccount->id);

        $this->assertNull($accountData);
    }

    #[Test]
    public function api_response_includes_user_id()
    {
        $account = TwoFAccount::factory()->for($this->user)->create([
            'is_shared' => true,
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->getJson("/api/v1/twofaccounts/{$account->id}");

        $response->assertOk()
                 ->assertJsonFragment([
                     'user_id' => $this->user->id,
                     'is_shared' => true,
                 ]);
    }
}
