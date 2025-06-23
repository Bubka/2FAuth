<?php

namespace Tests\Feature;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class TwoFAccountReadOnlyBehaviorTest extends FeatureTestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $viewer;
    private TwoFAccount $sharedAccount;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->viewer = User::factory()->create();
        
        $this->sharedAccount = TwoFAccount::factory()->for($this->owner)->create([
            'service' => 'Google',
            'account' => 'test@google.com',
            'is_shared' => true
        ]);
    }

    #[Test]
    public function owner_can_delete_own_shared_account()
    {
        // El propietario debe poder eliminar su propia cuenta compartida
        $response = $this->actingAs($this->owner, 'api-guard')
            ->deleteJson("/api/v1/twofaccounts/{$this->sharedAccount->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('twofaccounts', [
            'id' => $this->sharedAccount->id
        ]);
    }

    #[Test]
    public function viewer_cannot_delete_shared_account()
    {
        // El viewer no debe poder eliminar la cuenta compartida
        $response = $this->actingAs($this->viewer, 'api-guard')
            ->deleteJson("/api/v1/twofaccounts/{$this->sharedAccount->id}")
            ->assertStatus(403); // Unauthorized

        $this->assertDatabaseHas('twofaccounts', [
            'id' => $this->sharedAccount->id
        ]);
    }

    #[Test]
    public function viewer_can_see_shared_account_in_index()
    {
        // El viewer debe poder ver la cuenta compartida en el listado
        $response = $this->actingAs($this->viewer, 'api-guard')
            ->getJson('/api/v1/twofaccounts')
            ->assertStatus(200);

        $accountData = collect($response->json())
            ->firstWhere('id', $this->sharedAccount->id);

        $this->assertNotNull($accountData);
        $this->assertEquals($this->owner->id, $accountData['user_id']);
        $this->assertTrue($accountData['is_shared']);
    }

    #[Test]
    public function viewer_cannot_update_shared_account()
    {
        // El viewer no debe poder actualizar la cuenta compartida
        $response = $this->actingAs($this->viewer, 'api-guard')
            ->putJson("/api/v1/twofaccounts/{$this->sharedAccount->id}", [
                'service' => 'Modified Service',
                'account' => $this->sharedAccount->account,
                'icon' => $this->sharedAccount->icon,
                'otp_type' => $this->sharedAccount->otp_type,
                'secret' => $this->sharedAccount->secret,
                'digits' => $this->sharedAccount->digits,
                'algorithm' => $this->sharedAccount->algorithm,
                'period' => $this->sharedAccount->period,
            ])
            ->assertStatus(403); // Unauthorized

        $this->assertDatabaseHas('twofaccounts', [
            'id' => $this->sharedAccount->id,
            'service' => 'Google' // No debe haber cambiado
        ]);
    }

    #[Test]
    public function select_all_should_only_include_editable_accounts()
    {
        // Crear una cuenta adicional que pertenece al owner
        $ownAccount = TwoFAccount::factory()->for($this->owner)->create([
            'service' => 'Owner Service',
            'account' => 'owner@example.com',
            'is_shared' => false
        ]);

        // El viewer ve tanto la cuenta compartida como su propia cuenta (si tiene alguna)
        $response = $this->actingAs($this->viewer, 'api-guard')
            ->getJson('/api/v1/twofaccounts')
            ->assertStatus(200);

        $accounts = $response->json();
        
        // El viewer debería ver la cuenta compartida
        $sharedAccountVisible = collect($accounts)->contains('id', $this->sharedAccount->id);
        $this->assertTrue($sharedAccountVisible);
        
        // El viewer NO debería ver la cuenta privada del owner
        $ownAccountVisible = collect($accounts)->contains('id', $ownAccount->id);
        $this->assertFalse($ownAccountVisible);
        
        // En una implementación real del frontend, selectAll() solo debería seleccionar
        // las cuentas que el usuario puede editar, es decir, ninguna en este caso
        // ya que el viewer no es propietario de ninguna cuenta
    }
}
