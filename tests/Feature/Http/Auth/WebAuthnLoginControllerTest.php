<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\DB;
use Laragear\WebAuthn\Http\Requests\AssertedRequest;

class WebAuthnLoginControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
    */
    protected $user;

    const CREDENTIAL_ID = 's06aG41wsIYh5X1YUhB-SlH8y3F2RzdJZVse8iXRXOCd3oqQdEyCOsBawzxrYBtJRQA2azAMEN_q19TUp6iMgg';
    const PUBLIC_KEY = 'eyJpdiI6ImYyUHlJOEJML0pwTXJ2UDkveTQwZFE9PSIsInZhbHVlIjoiQWFSYi9LVEszazlBRUZsWHp0cGNRNktGeEQ3aTBsbU9zZ1g5MEgrWFJJNmgraElsNU9hV0VsRVlWc3NoUVVHUjRRdlcxTS9pVklnOWtVYWY5TFJQTTFhR1Rxb1ZzTFkxTWE4VUVvK1lyU3pYQ1M3VlBMWWxZcDVaYWFnK25iaXVyWGR6ZFRmMFVoSmdPZ3UvSnptbVZER0FYdEEyYmNYcW43RkV5aTVqSjNwZEFsUjhUYSs0YjU2Z2V2bUJXa0E0aVB1VC8xSjdJZ2llRGlHY2RwOGk3MmNPTyt6eDFDWUs1dVBOSWp1ZUFSeUlkclgwRW16RE9sUUpDSWV6Sk50TSIsIm1hYyI6IjI3ODQ5NzcxZGY1MzMwYTNiZjAwZmEwMDJkZjYzMGU4N2UzZjZlOGM0ZWE3NDkyYWMxMThhNmE5NWZiMTVjNGEiLCJ0YWciOiIifQ==';
    const USER_ID = '3b758ac868b74307a7e96e69ae187339';

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
    public function test_webauthn_login_uses_login_and_returns_no_content()
    {
        $this->user = User::factory()->create();

        $mock = $this->mock(AssertedRequest::class)->makePartial()->shouldIgnoreMissing();
        $mock->shouldReceive([
            'has' => false,
            'login' => $this->user,
        ]);

        $this->json('POST', '/webauthn/login')
            ->assertNoContent();
    }


    /**
     * @test
     */
    public function test_webauthn_invalid_login_returns_error()
    {
        $this->user = User::factory()->create();

        $mock = $this->mock(AssertedRequest::class)->makePartial()->shouldIgnoreMissing();
        $mock->shouldReceive([
            'has' => false,
            'login' => null,
        ]);

        $this->json('POST', '/webauthn/login')
            ->assertNoContent(422);
    }


    /**
     * @test
     */
    public function test_webauthn_login_with_missing_data_returns_validation_error()
    {
        $this->user = User::factory()->create();

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
        $this->user = User::factory()->create();

        DB::table('webauthn_credentials')->insert([
            'id' => self::CREDENTIAL_ID,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id' => $this->user->id,
            'user_id' => self::USER_ID,
            'counter' => 0,
            'rp_id' => 'http://localhost',
            'origin' => 'http://localhost',
            'aaguid' => '00000000-0000-0000-0000-000000000000',
            'attestation_format' => 'none',
            'public_key' => self::PUBLIC_KEY,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        $response = $this->json('POST', '/webauthn/login/options')
        ->assertOk()
        ->assertJsonStructure([
            'challenge',
            'userVerification',
            'timeout',
        ])
        ->assertJsonFragment([
            'allowCredentials' => [[
                'id' => self::CREDENTIAL_ID,
                'type' => 'public-key'
            ]],
        ]);
    }


    /**
     * @test
     */
    public function test_get_options_with_no_registred_user_returns_error()
    {
        $this->json('POST', '/webauthn/login/options')
        ->assertStatus(400)
        ->assertJsonStructure([
            'message',
        ]);
    }

}