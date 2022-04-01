<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webauthn\TrustPath\EmptyTrustPath;
use DarkGhostHunter\Larapass\Eloquent\WebAuthnCredential;
use DarkGhostHunter\Larapass\WebAuthn\WebAuthnAssertValidator;

class WebAuthnLoginControllerTest extends FeatureTestCase
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

        DB::table('users')->delete();
    }


    /**
     * @test
     */
    public function test_user_login_returns_success()
    {
        $this->user = User::factory()->create([
            'name'     => 'john',
            'email'    => 'john.doe@mail.com',
            'password' => '$2y$10$FLIykVJWDsYSVMJyaFZZfe4tF5uBTnGsosJBL.ZfAAHsYgc27FSdi',
        ]);
        $uuid = Str::uuid();

        DB::table('web_authn_credentials')->insert([
            'id'               => 'dGVzdF9jcmVkZW50aWFsX2lk',
            'user_id'          => $this->user->id,
            'type'             => 'public_key',
            'transports'       => json_encode([]),
            'attestation_type' => 'none',
            'trust_path'       => json_encode(['type' => EmptyTrustPath::class]),
            'aaguid'           => $uuid->toString(),
            'public_key'       => 'public_key',
            'counter'          => 0,
            'user_handle'      => 'test_user_handle',
            'created_at'       => now()->toDateTimeString(),
            'updated_at'       => now()->toDateTimeString(),
        ]);

        $data = [
            'id'       => 'dGVzdF9jcmVkZW50aWFsX2lk',
            'rawId'    => 'ZEdWemRGOWpjbVZrWlc1MGFXRnNYMmxr',
            'type'     => 'test_type',
            'response' => [
                'authenticatorData' => 'test',
                'clientDataJSON' => 'test',
                'signature' => 'test',
                'userHandle' => 'test',
            ],
        ];

        $this->mock(WebAuthnAssertValidator::class)
            ->shouldReceive('validate')
            ->with($data)
            ->andReturnUsing(function ($data) {
                $credentials = WebAuthnCredential::find($data['id']);

                $credentials->setAttribute('counter', 1)->save();

                return $credentials->toCredentialSource();
            });

        $this->json('POST', '/webauthn/login', $data)
            ->assertNoContent();

        $this->assertAuthenticatedAs($this->user);
    }


    /**
     * @test
     */
    public function test_user_login_without_userhandle_returns_success()
    {
        $this->user = User::factory()->create([
            'name'     => 'john',
            'email'    => 'john.doe@mail.com',
            'password' => '$2y$10$FLIykVJWDsYSVMJyaFZZfe4tF5uBTnGsosJBL.ZfAAHsYgc27FSdi',
        ]);
        $uuid = Str::uuid();

        DB::table('web_authn_credentials')->insert([
            'id'               => 'dGVzdF9jcmVkZW50aWFsX2lk',
            'user_id'          => $this->user->id,
            'type'             => 'public_key',
            'transports'       => json_encode([]),
            'attestation_type' => 'none',
            'trust_path'       => json_encode(['type' => EmptyTrustPath::class]),
            'aaguid'           => $uuid->toString(),
            'public_key'       => 'public_key',
            'counter'          => 0,
            'user_handle'      => 'test_user_handle',
            'created_at'       => now()->toDateTimeString(),
            'updated_at'       => now()->toDateTimeString(),
        ]);

        $data = [
            'id'       => 'dGVzdF9jcmVkZW50aWFsX2lk',
            'rawId'    => 'ZEdWemRGOWpjbVZrWlc1MGFXRnNYMmxr',
            'type'     => 'test_type',
            'response' => [
                'authenticatorData' => 'test',
                'clientDataJSON' => 'test',
                'signature' => 'test',
                'userHandle' => '',
            ],
        ];

        $this->mock(WebAuthnAssertValidator::class)
            ->shouldReceive('validate')
            ->with([
                'id'       => 'dGVzdF9jcmVkZW50aWFsX2lk',
                'rawId'    => 'ZEdWemRGOWpjbVZrWlc1MGFXRnNYMmxr',
                'type'     => 'test_type',
                'response' => [
                    'authenticatorData' => 'test',
                    'clientDataJSON' => 'test',
                    'signature' => 'test',
                    'userHandle' => 'dGVzdF91c2VyX2hhbmRsZQ==',
                ],
            ])
            ->andReturnUsing(function ($data) {
                $credentials = WebAuthnCredential::find($data['id']);

                $credentials->setAttribute('counter', 1)->save();

                return $credentials->toCredentialSource();
            });

        $this->json('POST', '/webauthn/login', $data)
            ->assertNoContent();

        $this->assertAuthenticatedAs($this->user);
    }


    /**
     * @test
     */
    public function test_user_login_with_missing_data_returns_validation_error()
    {
        $this->user = User::factory()->create([
            'name'     => 'john',
            'email'    => 'john.doe@mail.com',
            'password' => '$2y$10$FLIykVJWDsYSVMJyaFZZfe4tF5uBTnGsosJBL.ZfAAHsYgc27FSdi',
        ]);

        $data = [
            'id'       => '',
            'rawId'    => '',
            'type'     => '',
            'response' => [
                'authenticatorData' => '',
                'clientDataJSON' => '',
                'signature' => '',
                'userHandle' => null,
            ],
        ];

        $response = $this->json('POST', '/webauthn/login', $data)
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'id',
            'rawId',
            'type',
            'response.authenticatorData',
            'response.clientDataJSON',
            'response.signature',
        ]);
    }


    /**
     * @test
     */
    public function test_get_options_returns_success()
    {
        $this->user = User::factory()->create([
            'name'     => 'john',
            'email'    => 'john.doe@mail.com',
            'password' => '$2y$10$FLIykVJWDsYSVMJyaFZZfe4tF5uBTnGsosJBL.ZfAAHsYgc27FSdi',
        ]);

        $response = $this->json('POST', '/webauthn/login/options', [])
        ->assertOk()
        ->assertJsonStructure([
            'challenge',
            'rpId',
            'userVerification',
            'timeout',
        ]);
    }


    /**
     * @test
     */
    public function test_get_options_with_no_registred_user_returns_error()
    {
        $this->json('POST', '/webauthn/login/options', [])
        ->assertStatus(400)
        ->assertJsonStructure([
            'message',
        ]);
    }

}