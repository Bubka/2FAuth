<?php

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Laragear\WebAuthn\Assertion\Validator\AssertionValidator;
use Laragear\WebAuthn\WebAuthn;
use Tests\FeatureTestCase;

/**
 * @covers  \App\Http\Controllers\Auth\WebAuthnLoginController
 * @covers  \App\Models\User
 * @covers  \App\Extensions\WebauthnTwoFAuthUserProvider
 */
class WebAuthnLoginControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    const CREDENTIAL_ID = 's06aG41wsIYh5X1YUhB-SlH8y3F2RzdJZVse8iXRXOCd3oqQdEyCOsBawzxrYBtJRQA2azAMEN_q19TUp6iMgg';

    const CREDENTIAL_ID_ALT = '-VOLFKPY-_FuMI_sJ7gMllK76L3VoRUINj6lL_Z3qDg';

    const CREDENTIAL_ID_ALT_RAW = '+VOLFKPY+/FuMI/sJ7gMllK76L3VoRUINj6lL/Z3qDg=';

    const PUBLIC_KEY = 'eyJpdiI6ImYyUHlJOEJML0pwTXJ2UDkveTQwZFE9PSIsInZhbHVlIjoiQWFSYi9LVEszazlBRUZsWHp0cGNRNktGeEQ3aTBsbU9zZ1g5MEgrWFJJNmgraElsNU9hV0VsRVlWc3NoUVVHUjRRdlcxTS9pVklnOWtVYWY5TFJQTTFhR1Rxb1ZzTFkxTWE4VUVvK1lyU3pYQ1M3VlBMWWxZcDVaYWFnK25iaXVyWGR6ZFRmMFVoSmdPZ3UvSnptbVZER0FYdEEyYmNYcW43RkV5aTVqSjNwZEFsUjhUYSs0YjU2Z2V2bUJXa0E0aVB1VC8xSjdJZ2llRGlHY2RwOGk3MmNPTyt6eDFDWUs1dVBOSWp1ZUFSeUlkclgwRW16RE9sUUpDSWV6Sk50TSIsIm1hYyI6IjI3ODQ5NzcxZGY1MzMwYTNiZjAwZmEwMDJkZjYzMGU4N2UzZjZlOGM0ZWE3NDkyYWMxMThhNmE5NWZiMTVjNGEiLCJ0YWciOiIifQ==';

    const USER_ID = '3b758ac868b74307a7e96e69ae187339';

    const USER_ID_ALT = 'e8af6f703f8042aa91c30cf72289aa07';

    const EMAIL = 'john.doe@example.com';

    const ASSERTION_RESPONSE = [
        'id'       => self::CREDENTIAL_ID_ALT,
        'rawId'    => self::CREDENTIAL_ID_ALT_RAW,
        'type'     => 'public-key',
        'response' => [
            'clientDataJSON'    => 'eyJ0eXBlIjoid2ViYXV0aG4uZ2V0IiwiY2hhbGxlbmdlIjoiaVhvem15bktpLVlEMmlSdktOYlNQQSIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9',
            'authenticatorData' => 'SZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2MFAAAAAQ==',
            'signature'         => 'ca4IJ9h8bZnjMbEFuHX1zfX5LcbiPyDVz6sD1/ppR4t8++1DxKa5EdBIrfNlo8FSOv/JSzMrGGUCQvc/Ngj1KnZpO3s9OdTb54/gMDewH/K8EG4wSvxzHdL6sMbP7UUc5Wq1pcdu9MgXY8V+1gftXpzcoaae0X+mLEETgU7eB8jG0mZhVWvE4yQKuDnZA1i9r8oQhqsvG4nUw1BxvR8wAGiRR+R287LaL41k+xum5mS8zEojUmuLSH50miyVxZ4Y+/oyfxG7i+wSYGNSXlW5iNPB+2WupGS7ce4TuOgaFeMmP2a9rzP4m2IBSQoJ2FyrdzR7HwBEewqqrUVbGQw3Aw==',
            'userHandle'        => self::USER_ID_ALT,
        ],
        'email' => self::EMAIL,
    ];

    const ASSERTION_RESPONSE_NO_HANDLE = [
        'id'       => self::CREDENTIAL_ID_ALT,
        'rawId'    => self::CREDENTIAL_ID_ALT_RAW,
        'type'     => 'public-key',
        'response' => [
            'clientDataJSON'    => 'eyJ0eXBlIjoid2ViYXV0aG4uZ2V0IiwiY2hhbGxlbmdlIjoiaVhvem15bktpLVlEMmlSdktOYlNQQSIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9',
            'authenticatorData' => 'SZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2MFAAAAAQ==',
            'signature'         => 'ca4IJ9h8bZnjMbEFuHX1zfX5LcbiPyDVz6sD1/ppR4t8++1DxKa5EdBIrfNlo8FSOv/JSzMrGGUCQvc/Ngj1KnZpO3s9OdTb54/gMDewH/K8EG4wSvxzHdL6sMbP7UUc5Wq1pcdu9MgXY8V+1gftXpzcoaae0X+mLEETgU7eB8jG0mZhVWvE4yQKuDnZA1i9r8oQhqsvG4nUw1BxvR8wAGiRR+R287LaL41k+xum5mS8zEojUmuLSH50miyVxZ4Y+/oyfxG7i+wSYGNSXlW5iNPB+2WupGS7ce4TuOgaFeMmP2a9rzP4m2IBSQoJ2FyrdzR7HwBEewqqrUVbGQw3Aw==',
            'userHandle'        => null,
        ],
        'email' => self::EMAIL,
    ];

    const ASSERTION_RESPONSE_INVALID = [
        'id'       => self::CREDENTIAL_ID_ALT,
        'rawId'    => self::CREDENTIAL_ID_ALT_RAW,
        'type'     => 'public-key',
        'response' => [
            'clientDataJSON'    => 'eyJ0eXBlIjoid2ViYXV0aG4uZ2V0IiwiY2hhbGxlbmdlIjoiaVhvem15bktpLVlEMmlSdktOYlNQQSIsIm9yaWdpbiI6Imh0dHA6Ly9sb2NhbGhvc3QiLCJjcm9zc09yaWdpbiI6ZmFsc2V9',
            'authenticatorData' => 'SZYN5YgOjGh0NBcPZHZgW4/krrmihjLHmVzzuoMdl2MFAAAAAQ==',
            'signature'         => 'ca4IJ9h8bZnjMbEFuHX1zfX5LcbiPyDVz6sD1/ppR4t8++1DxKa5EdBIrfNlo8FSOv/JSzMrGGUCQvc/Ngj1KnZpO3s9OdTb54/gMDewH/K8EG4wSvxzHdL6sMbP7UUc5Wq1pcdu9MgXY8V+1gftXpzcoaae0X+mLEETgU7eB8jG0mZhVWvE4yQKuDnZA1i9r8oQhqsvG4nUw1BxvR8wAGiRR+R287LaL41k+xum5mS8zEojUmuLSH50miyVxZ4Y+/oyfxG7i+wSYGNSXlW5iNPB+2WupGS7ce4TuOgaFeMmP2a9rzP4m2IBSQoJ2FyrdzR7HwBEewqqrUVbGQw3Aw==',
            'userHandle'        => self::USER_ID_ALT,
        ],
        'email' => self::EMAIL,
    ];

    const ASSERTION_CHALLENGE = 'iXozmynKi+YD2iRvKNbSPA==';

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        DB::table('users')->delete();
    }

    /**
     * @test
     */
    public function test_webauthn_login_returns_success()
    {
        $this->user = User::factory()->create(['email' => self::EMAIL]);

        DB::table('webauthn_credentials')->insert([
            'id'                   => self::CREDENTIAL_ID_ALT,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $this->user->id,
            'user_id'              => self::USER_ID_ALT,
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => self::PUBLIC_KEY,
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);

        $this->session(['_webauthn' => new \Laragear\WebAuthn\Challenge(
            new \Laragear\WebAuthn\ByteBuffer(base64_decode(self::ASSERTION_CHALLENGE)),
            60,
            false,
        )]);

        $this->mock(AssertionValidator::class)
            ->expects('send->thenReturn')
            ->andReturn();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'authenticated',
                'name'    => $this->user->name,
            ])
            ->assertJsonStructure([
                'message',
                'name',
                'preferences',
            ]);
    }

    /**
     * @test
     */
    public function test_webauthn_login_merge_handle_if_missing()
    {
        $this->user = User::factory()->create(['email' => self::EMAIL]);

        DB::table('webauthn_credentials')->insert([
            'id'                   => self::CREDENTIAL_ID_ALT,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $this->user->id,
            'user_id'              => self::USER_ID_ALT,
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => self::PUBLIC_KEY,
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);

        $this->session(['_webauthn' => new \Laragear\WebAuthn\Challenge(
            new \Laragear\WebAuthn\ByteBuffer(base64_decode(self::ASSERTION_CHALLENGE)),
            60,
            false,
        )]);

        $this->mock(AssertionValidator::class)
            ->expects('send->thenReturn')
            ->andReturn();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_NO_HANDLE)
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'authenticated',
                'name'    => $this->user->name,
            ])
            ->assertJsonStructure([
                'message',
                'name',
                'preferences',
            ]);
    }

    /**
     * @test
     */
    public function test_legacy_login_is_rejected_when_webauthn_only_is_enable()
    {
        $this->user = User::factory()->create([
            'email' => self::EMAIL,
        ]);

        // Set to webauthn only
        $this->user['preferences->useWebauthnOnly'] = true;
        $this->user->save();

        $response = $this->json('POST', '/user/login', [
            'email'    => self::EMAIL,
            'password' => 'password',
        ])
            ->assertUnauthorized();
    }

    /**
     * @test
     *
     * @covers  \App\Http\Middleware\SkipIfAuthenticated
     */
    public function test_webauthn_login_already_authenticated_returns_success()
    {
        $this->user = User::factory()->create(['email' => self::EMAIL]);

        DB::table('webauthn_credentials')->insert([
            'id'                   => self::CREDENTIAL_ID_ALT,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $this->user->id,
            'user_id'              => self::USER_ID_ALT,
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => self::PUBLIC_KEY,
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);

        $this->session(['_webauthn' => new \Laragear\WebAuthn\Challenge(
            new \Laragear\WebAuthn\ByteBuffer(base64_decode(self::ASSERTION_CHALLENGE)),
            60,
            false,
        )]);

        $this->mock(AssertionValidator::class)
            ->expects('send->thenReturn')
            ->andReturn();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'authenticated',
                'name'    => $this->user->name,
            ])
            ->assertJsonStructure([
                'message',
                'name',
                'preferences',
            ]);
    }

    /**
     * @test
     */
    public function test_webauthn_login_with_missing_data_returns_validation_error()
    {
        $this->user = User::factory()->create(['email' => self::EMAIL]);

        $data = [
            'id'       => '',
            'rawId'    => '',
            'type'     => '',
            'response' => [
                'authenticatorData' => '',
                'clientDataJSON'    => '',
                'signature'         => '',
                'userHandle'        => null,
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
    public function test_webauthn_invalid_login_returns_unauthorized()
    {
        $this->user = User::factory()->create(['email' => self::EMAIL]);

        $this->session(['_webauthn' => new \Laragear\WebAuthn\Challenge(
            new \Laragear\WebAuthn\ByteBuffer(base64_decode(self::ASSERTION_CHALLENGE)),
            60,
            false,
        )]);

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_INVALID)
            ->assertUnauthorized();
    }

    /**
     * @test
     */
    public function test_too_many_invalid_login_attempts_returns_too_many_request_error()
    {
        $throttle = 8;
        Config::set('auth.throttle.login', $throttle);

        $this->user = User::factory()->create(['email' => self::EMAIL]);

        $this->session(['_webauthn' => new \Laragear\WebAuthn\Challenge(
            new \Laragear\WebAuthn\ByteBuffer(base64_decode(self::ASSERTION_CHALLENGE)),
            60,
            false,
        )]);

        for ($i = 0; $i < $throttle - 1; $i++) {
            $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_INVALID);
        }

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_INVALID)
            ->assertUnauthorized();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_INVALID)
        ->assertStatus(429);
    }

    /**
     * @test
     */
    public function test_get_options_returns_success()
    {
        Config::set('webauthn.user_verification', WebAuthn::USER_VERIFICATION_PREFERRED);

        $this->user = User::factory()->create(['email' => self::EMAIL]);

        DB::table('webauthn_credentials')->insert([
            'id'                   => self::CREDENTIAL_ID,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $this->user->id,
            'user_id'              => self::USER_ID,
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => self::PUBLIC_KEY,
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);

        $response = $this->json('POST', '/webauthn/login/options', [
            'email' => $this->user->email,
        ])
            ->assertOk()
            ->assertJsonStructure([
                'challenge',
                'timeout',
            ])
            ->assertJsonFragment([
                'allowCredentials' => [[
                    'id'   => self::CREDENTIAL_ID,
                    'type' => 'public-key',
                ]],
            ]);
    }

    /**
     * @test
     */
    public function test_get_options_for_securelogin_returns_required_userVerification()
    {
        Config::set('webauthn.user_verification', WebAuthn::USER_VERIFICATION_REQUIRED);

        $this->user = User::factory()->create(['email' => self::EMAIL]);

        DB::table('webauthn_credentials')->insert([
            'id'                   => self::CREDENTIAL_ID,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $this->user->id,
            'user_id'              => self::USER_ID,
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => self::PUBLIC_KEY,
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);

        $response = $this->json('POST', '/webauthn/login/options', [
            'email' => $this->user->email,
        ])
            ->assertOk()
            ->assertJsonStructure([
                'challenge',
                'userVerification',
                'timeout',
            ])
            ->assertJsonFragment([
                'userVerification' => 'required',
                'allowCredentials' => [[
                    'id'   => self::CREDENTIAL_ID,
                    'type' => 'public-key',
                ]],
            ]);
    }

    /**
     * @test
     */
    public function test_get_options_for_fastlogin_returns_discouraged_userVerification()
    {
        Config::set('webauthn.user_verification', WebAuthn::USER_VERIFICATION_DISCOURAGED);

        $this->user = User::factory()->create(['email' => self::EMAIL]);

        DB::table('webauthn_credentials')->insert([
            'id'                   => self::CREDENTIAL_ID,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $this->user->id,
            'user_id'              => self::USER_ID,
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => self::PUBLIC_KEY,
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);

        $response = $this->json('POST', '/webauthn/login/options', [
            'email' => $this->user->email,
        ])
            ->assertOk()
            ->assertJsonStructure([
                'challenge',
                'userVerification',
                'timeout',
            ])
            ->assertJsonFragment([
                'userVerification' => 'discouraged',
                'allowCredentials' => [[
                    'id'   => self::CREDENTIAL_ID,
                    'type' => 'public-key',
                ]],
            ]);
    }

    /**
     * @test
     */
    public function test_get_options_with_capitalized_email_returns_success()
    {
        $this->user = User::factory()->create(['email' => self::EMAIL]);

        $this->json('POST', '/webauthn/login/options', [
            'email' => strtoupper($this->user->email),
        ])
            ->assertOk();
    }

    /**
     * @test
     */
    public function test_get_options_with_missing_email_returns_validation_errors()
    {
        $this->json('POST', '/webauthn/login/options', [
            'email' => null,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    /**
     * @test
     */
    public function test_get_options_with_invalid_email_returns_validation_errors()
    {
        $this->json('POST', '/webauthn/login/options', [
            'email' => 'invalid',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    /**
     * @test
     */
    public function test_get_options_with_unknown_email_returns_validation_errors()
    {
        $this->json('POST', '/webauthn/login/options', [
            'email' => 'john@example.com',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }
}
