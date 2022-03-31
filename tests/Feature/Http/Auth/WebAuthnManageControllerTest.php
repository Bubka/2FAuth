<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webauthn\TrustPath\EmptyTrustPath;

class WebAuthnManageControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
    */
    protected $user;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }


    /**
     * @test
     */
    public function test_index_returns_success_with_credentials()
    {
        DB::table('web_authn_credentials')->insert([
            'id'               => 'test_credential_id',
            'user_id'          => $this->user->id,
            'type'             => 'public_key',
            'transports'       => json_encode([]),
            'attestation_type' => 'none',
            'trust_path'       => json_encode(['type' => EmptyTrustPath::class]),
            'aaguid'           => Str::uuid(),
            'public_key'       => 'public_key_bar',
            'counter'          => 0,
            'user_handle'      => 'test_id',
            'created_at'       => now()->toDateTimeString(),
            'updated_at'       => now()->toDateTimeString(),
            'disabled_at'      => null,
        ]);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('GET', '/webauthn/credentials')
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'type',
                    'transports'
                ]
            ]);
    }


    /**
     * @test
     */
    public function test_rename_returns_success_with_new_name()
    {
        DB::table('web_authn_credentials')->insert([
            'id'               => 'test_credential_id',
            'name'             => 'MyCredential',
            'user_id'          => $this->user->id,
            'type'             => 'public_key',
            'transports'       => json_encode([]),
            'attestation_type' => 'none',
            'trust_path'       => json_encode(['type' => EmptyTrustPath::class]),
            'aaguid'           => Str::uuid(),
            'public_key'       => 'public_key_bar',
            'counter'          => 0,
            'user_handle'      => 'test_id',
            'created_at'       => now()->toDateTimeString(),
            'updated_at'       => now()->toDateTimeString(),
            'disabled_at'      => null,
        ]);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PATCH', '/webauthn/credentials/test_credential_id/name',[
                'name' => 'MyNewCredential',
            ])
            ->assertStatus(200)
            ->assertExactJson([
                'name' => 'MyNewCredential',
            ]);
    }


    /**
     * @test
     */
    public function test_rename_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PATCH', '/webauthn/credentials/test_credential_id/name', [
                'name' => null,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_rename_missing_credential_returns_not_found()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PATCH', '/webauthn/credentials/unknown/name', [
                'name' => 'MyNewCredential',
            ])
            ->assertNotFound()
            ->assertJsonStructure([
                'message'
            ]);
    }


    /**
     * @test
     */
    public function test_index_as_reverse_proxy_returns_error()
    {
        $response = $this->actingAs($this->user, 'reverse-proxy-guard')
            ->json('GET', '/webauthn/credentials')
            ->assertStatus(400);
    }


    /**
     * @test
     */
    public function test_rename_as_reverse_proxy_returns_error()
    {
        $response = $this->actingAs($this->user, 'reverse-proxy-guard')
            ->json('PATCH', '/webauthn/credentials/fqsdfqsdf/name')
            ->assertStatus(400);
    }


    /**
     * @test
     */
    public function test_delete_as_reverse_proxy_returns_error()
    {
        $response = $this->actingAs($this->user, 'reverse-proxy-guard')
            ->json('DELETE', '/webauthn/credentials/dcnskldjnkljsrn')
            ->assertStatus(400);
    }


    /**
     * @test
     */
    public function test_delete_returns_no_content()
    {
        $response = $this->actingAs($this->user, 'web-guard')
            ->json('DELETE', '/webauthn/credentials/sdCKktnsdK')
            ->assertNoContent();
    }

}