<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Tests\FeatureTestCase;

/**
 * @covers  \App\Http\Controllers\Auth\WebAuthnRecoveryController
 * @covers  \App\Extensions\WebauthnCredentialBroker
 * @covers  \App\Http\Requests\WebauthnRecoveryRequest
 * @covers  \App\Providers\AuthServiceProvider
 */
class WebAuthnRecoveryControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    protected $now;

    const STORED_TOKEN_VALUE = '$2y$10$P6q8rl8te5QaO1EdpyJcNO0s9VFlVgf62KaItQhrPTskxfyu97mlW';

    const ACTUAL_TOKEN_VALUE = '9e583e3fb6c32034164ac62415c9657dcbd1fb861b434340b08a94c2075cac66';

    const CREDENTIAL_ID = '-VOLFKPY-_FuMI_sJ7gMllK76L3VoRUINj6lL_Z3qDg';

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Date::setTestNow($this->now = Date::create(2022, 11, 16, 9, 4));

        DB::table('webauthn_recoveries')->insert([
            'email'      => $this->user->email,
            'token'      => self::STORED_TOKEN_VALUE,
            'created_at' => $this->now->toDateTimeString(),
        ]);
    }

    /**
     * @test
     */
    public function test_recover_fails_if_no_recovery_is_set()
    {
        DB::table('webauthn_recoveries')->delete();

        $this->json('POST', '/webauthn/recover', [
            'token'    => self::ACTUAL_TOKEN_VALUE,
            'email'    => $this->user->email,
            'password' => UserFactory::USER_PASSWORD,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('token');
    }

    /**
     * @test
     */
    public function test_recover_with_wrong_token_returns_validation_error()
    {
        $response = $this->json('POST', '/webauthn/recover', [
            'token'    => 'wrong_token',
            'email'    => $this->user->email,
            'password' => UserFactory::USER_PASSWORD,
        ])
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors('email')
            ->assertJsonValidationErrors('token');
    }

    /**
     * @test
     */
    public function test_recover_with_expired_token_returns_validation_error()
    {
        Date::setTestNow($now = Date::create(2020, 01, 01, 16, 30));

        DB::table('webauthn_recoveries')->delete();
        DB::table('webauthn_recoveries')->insert([
            'token'      => self::STORED_TOKEN_VALUE,
            'email'      => $this->user->email,
            'created_at' => $now->clone()->subHour()->subSecond()->toDateTimeString(),
        ]);

        $this->json('POST', '/webauthn/recover', [
            'token'    => self::ACTUAL_TOKEN_VALUE,
            'email'    => $this->user->email,
            'password' => UserFactory::USER_PASSWORD,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('token');
    }

    /**
     * @test
     */
    public function test_recover_with_invalid_password_returns_authentication_error()
    {
        $this->json('POST', '/webauthn/recover', [
            'token'    => self::ACTUAL_TOKEN_VALUE,
            'email'    => $this->user->email,
            'password' => 'bad_password',
        ])
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function test_recover_returns_validation_error_when_no_user_exists()
    {
        $this->json('POST', '/webauthn/recover', [
            'token'    => self::ACTUAL_TOKEN_VALUE,
            'email'    => 'no@user.com',
            'password' => UserFactory::USER_PASSWORD,
        ])
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors('password')
            ->assertJsonMissingValidationErrors('token')
            ->assertJsonValidationErrors('email');
    }

    /**
     * @test
     */
    public function test_recover_returns_success()
    {
        $response = $this->json('POST', '/webauthn/recover', [
            'token'    => self::ACTUAL_TOKEN_VALUE,
            'email'    => $this->user->email,
            'password' => UserFactory::USER_PASSWORD,
        ])
            ->assertStatus(200);

        $this->assertDatabaseMissing('webauthn_recoveries', [
            'token' => self::STORED_TOKEN_VALUE,
        ]);

        $this->assertDatabaseMissing('options', [
            'key' => 'useWebauthnOnly',
        ]);
    }

    /**
     * @test
     */
    public function test_revoke_all_credentials_clear_registered_credentials()
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

        $response = $this->json('POST', '/webauthn/recover', [
            'token'     => self::ACTUAL_TOKEN_VALUE,
            'email'     => $this->user->email,
            'password'  => UserFactory::USER_PASSWORD,
            'revokeAll' => true,
        ])
            ->assertStatus(200);

        $this->assertDatabaseMissing('webauthn_credentials', [
            'authenticatable_id' => $this->user->id,
        ]);
    }
}
