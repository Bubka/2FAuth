<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\FeatureTestCase;

/**
 * @covers  \App\Http\Controllers\Auth\WebAuthnManageController
 * @covers  \App\Http\Middleware\RejectIfReverseProxy
 * @covers  \App\Models\Traits\WebAuthnManageCredentials
 */
class WebAuthnManageControllerTest extends FeatureTestCase
{
    // use WithoutMiddleware;

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    public const CREDENTIAL_ID = '-VOLFKPY-_FuMI_sJ7gMllK76L3VoRUINj6lL_Z3qDg';

    public const CREDENTIAL_ID_RAW = '+VOLFKPY+/FuMI/sJ7gMllK76L3VoRUINj6lL/Z3qDg=';

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function test_index_returns_success_with_credentials()
    {
        DB::table('webauthn_credentials')->insert([
            'id'                   => self::CREDENTIAL_ID,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $this->user->id,
            'user_id'              => 'e8af6f703f8042aa91c30cf72289aa07',
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => 'eyJpdiI6Imp0U0NVeFNNbW45KzEvMXpad2p2SUE9PSIsInZhbHVlIjoic0VxZ2I1WnlHM2lJakhkWHVkK2kzMWtibk1IN2ZlaExGT01qOElXMDdRTjhnVlR0TDgwOHk1S0xQUy9BQ1JCWHRLNzRtenNsMml1dVQydWtERjFEU0h0bkJGT2RwUXE1M1JCcVpablE2Y2VGV2YvVEE2RGFIRUE5L0x1K0JIQXhLVE1aNVNmN3AxeHdjRUo2V0hwREZSRTJYaThNNnB1VnozMlVXZEVPajhBL3d3ODlkTVN3bW54RTEwSG0ybzRQZFFNNEFrVytUYThub2IvMFRtUlBZamoyZElWKzR1bStZQ1IwU3FXbkYvSm1FU2FlMTFXYUo0SG9kc1BDME9CNUNKeE9IelE5d2dmNFNJRXBKNUdlVzJ3VHUrQWJZRFluK0hib0xvVTdWQ0ZISjZmOWF3by83aVJES1dxbU9Zd1lhRTlLVmhZSUdlWmlBOUFtcTM2ZVBaRWNKNEFSQUhENk5EaC9hN3REdnVFbm16WkRxekRWOXd4cVcvZFdKa2tlWWJqZWlmZnZLS0F1VEVCZEZQcXJkTExiNWRyQmxsZWtaSDRlT3VVS0ZBSXFBRG1JMjRUMnBKRXZxOUFUa2xxMjg2TEplUzdscVo2UytoVU5SdXk1OE1lcFN6aU05ZkVXTkdIM2tKM3Q5bmx1TGtYb1F5bGxxQVR3K3BVUVlia1VybDFKRm9lZDViNzYraGJRdmtUb2FNTEVGZmZYZ3lYRDRiOUVjRnJpcTVvWVExOHJHSTJpMnVBZ3E0TmljbUlKUUtXY2lSWDh1dE5MVDNRUzVRSkQrTjVJUU8rSGhpeFhRRjJvSEdQYjBoVT0iLCJtYWMiOiI5MTdmNWRkZGE5OTEwNzQ3MjhkYWVhYjRlNjk0MWZlMmI5OTQ4YzlmZWI1M2I4OGVkMjE1MjMxNjUwOWRmZTU2IiwidGFnIjoiIn0=',
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('GET', '/webauthn/credentials')
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'alias',
                ],
            ]);
    }

    /**
     * @test
     */
    public function test_rename_returns_success_with_new_name()
    {
        DB::table('webauthn_credentials')->insert([
            'id'                   => self::CREDENTIAL_ID,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $this->user->id,
            'user_id'              => 'e8af6f703f8042aa91c30cf72289aa07',
            'alias'                => 'MyOldCredential',
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => 'eyJpdiI6Imp0U0NVeFNNbW45KzEvMXpad2p2SUE9PSIsInZhbHVlIjoic0VxZ2I1WnlHM2lJakhkWHVkK2kzMWtibk1IN2ZlaExGT01qOElXMDdRTjhnVlR0TDgwOHk1S0xQUy9BQ1JCWHRLNzRtenNsMml1dVQydWtERjFEU0h0bkJGT2RwUXE1M1JCcVpablE2Y2VGV2YvVEE2RGFIRUE5L0x1K0JIQXhLVE1aNVNmN3AxeHdjRUo2V0hwREZSRTJYaThNNnB1VnozMlVXZEVPajhBL3d3ODlkTVN3bW54RTEwSG0ybzRQZFFNNEFrVytUYThub2IvMFRtUlBZamoyZElWKzR1bStZQ1IwU3FXbkYvSm1FU2FlMTFXYUo0SG9kc1BDME9CNUNKeE9IelE5d2dmNFNJRXBKNUdlVzJ3VHUrQWJZRFluK0hib0xvVTdWQ0ZISjZmOWF3by83aVJES1dxbU9Zd1lhRTlLVmhZSUdlWmlBOUFtcTM2ZVBaRWNKNEFSQUhENk5EaC9hN3REdnVFbm16WkRxekRWOXd4cVcvZFdKa2tlWWJqZWlmZnZLS0F1VEVCZEZQcXJkTExiNWRyQmxsZWtaSDRlT3VVS0ZBSXFBRG1JMjRUMnBKRXZxOUFUa2xxMjg2TEplUzdscVo2UytoVU5SdXk1OE1lcFN6aU05ZkVXTkdIM2tKM3Q5bmx1TGtYb1F5bGxxQVR3K3BVUVlia1VybDFKRm9lZDViNzYraGJRdmtUb2FNTEVGZmZYZ3lYRDRiOUVjRnJpcTVvWVExOHJHSTJpMnVBZ3E0TmljbUlKUUtXY2lSWDh1dE5MVDNRUzVRSkQrTjVJUU8rSGhpeFhRRjJvSEdQYjBoVT0iLCJtYWMiOiI5MTdmNWRkZGE5OTEwNzQ3MjhkYWVhYjRlNjk0MWZlMmI5OTQ4YzlmZWI1M2I4OGVkMjE1MjMxNjUwOWRmZTU2IiwidGFnIjoiIn0=',
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json('PATCH', '/webauthn/credentials/' . self::CREDENTIAL_ID . '/name', [
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
            ->json('PATCH', '/webauthn/credentials/' . self::CREDENTIAL_ID . '/name', [
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
                'message',
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
