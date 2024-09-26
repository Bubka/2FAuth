<?php

namespace Tests\Feature\Http\Auth;

use App\Extensions\WebauthnTwoFAuthUserProvider;
use App\Facades\Settings;
use App\Http\Controllers\Auth\WebAuthnLoginController;
use App\Http\Middleware\RejectIfSsoOnlyAndNotForAdmin;
use App\Listeners\Authentication\FailedLoginListener;
use App\Listeners\Authentication\LoginListener;
use App\Models\User;
use App\Notifications\SignedInWithNewDeviceNotification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Laragear\WebAuthn\Assertion\Validator\AssertionValidator;
use Laragear\WebAuthn\Enums\UserVerification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * WebAuthnLoginControllerTest test class
 */
#[CoversClass(WebAuthnLoginController::class)]
#[CoversClass(User::class)]
#[CoversClass(WebauthnTwoFAuthUserProvider::class)]
#[CoversClass(LoginListener::class)]
#[CoversClass(FailedLoginListener::class)]
#[CoversMethod(RejectIfSsoOnlyAndNotForAdmin::class, 'handle')]
class WebAuthnLoginControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    const CREDENTIAL_ID = 's06aG41wsIYh5X1YUhB-SlH8y3F2RzdJZVse8iXRXOCd3oqQdEyCOsBawzxrYBtJRQA2azAMEN_q19TUp6iMgg';

    const CREDENTIAL_ID_ALT = '-VOLFKPY-_FuMI_sJ7gMllK76L3VoRUINj6lL_Z3qDg';

    const CREDENTIAL_ID_ALT_RAW = '+VOLFKPY+/FuMI/sJ7gMllK76L3VoRUINj6lL/Z3qDg=';

    const PUBLIC_KEY = 'eyJpdiI6ImYyUHlJOEJML0pwTXJ2UDkveTQwZFE9PSIsInZhbHVlIjoiQWFSYi9LVEszazlBRUZsWHp0cGNRNktGeEQ3aTBsbU9zZ1g5MEgrWFJJNmgraElsNU9hV0VsRVlWc3NoUVVHUjRRdlcxTS9pVklnOWtVYWY5TFJQTTFhR1Rxb1ZzTFkxTWE4VUVvK1lyU3pYQ1M3VlBMWWxZcDVaYWFnK25iaXVyWGR6ZFRmMFVoSmdPZ3UvSnptbVZER0FYdEEyYmNYcW43RkV5aTVqSjNwZEFsUjhUYSs0YjU2Z2V2bUJXa0E0aVB1VC8xSjdJZ2llRGlHY2RwOGk3MmNPTyt6eDFDWUs1dVBOSWp1ZUFSeUlkclgwRW16RE9sUUpDSWV6Sk50TSIsIm1hYyI6IjI3ODQ5NzcxZGY1MzMwYTNiZjAwZmEwMDJkZjYzMGU4N2UzZjZlOGM0ZWE3NDkyYWMxMThhNmE5NWZiMTVjNGEiLCJ0YWciOiIifQ==';

    const USER_ID = '3b758ac868b74307a7e96e69ae187339';

    const USER_ID_ALT = 'e8af6f703f8042aa91c30cf72289aa07';

    const EMAIL = 'john.doe@example.com';

    private const GUARD = 'web-guard';

    private const AUTH_METHOD = 'webauthn';

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

    public function setUp() : void
    {
        parent::setUp();

        DB::table('users')->delete();

        $this->user = User::factory()->create(['email' => self::EMAIL]);

        $this->mock(AssertionValidator::class)
            ->shouldReceive('send->thenReturn')
            ->andReturn();
    }

    #[Test]
    public function test_webauthn_login_returns_success()
    {
        $this->createWebauthnCredential(self::CREDENTIAL_ID_ALT, $this->user->id, self::USER_ID_ALT);
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk()
            ->assertJsonFragment([
                'message'  => 'authenticated',
                'id'       => $this->user->id,
                'name'     => $this->user->name,
                'email'    => $this->user->email,
                'is_admin' => false,
            ])
            ->assertJsonStructure([
                'preferences',
            ]);
    }

    #[Test]
    public function test_webauthn_login_of_admin_returns_success_even_with_sso_only_enabled()
    {
        Settings::set('useSsoOnly', true);

        $this->user->promoteToAdministrator(true);
        $this->user->save();

        $this->createWebauthnCredential(self::CREDENTIAL_ID_ALT, $this->user->id, self::USER_ID_ALT);
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk()
            ->assertJsonFragment([
                'message'  => 'authenticated',
                'id'       => $this->user->id,
                'name'     => $this->user->name,
                'email'    => $this->user->email,
                'is_admin' => true,
            ])
            ->assertJsonStructure([
                'preferences',
            ]);

        $this->user->promoteToAdministrator(false);
        $this->user->save();
    }

    #[Test]
    public function test_webauthn_login_sends_new_device_notification_to_existing_user()
    {
        Notification::fake();

        $this->user['preferences->notifyOnNewAuthDevice'] = 1;
        $this->user->save();

        $this->createWebauthnCredential(self::CREDENTIAL_ID_ALT, $this->user->id, self::USER_ID_ALT);
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk();

        $this->actingAs($this->user, self::GUARD)
            ->json('GET', '/user/logout');

        $this->travel(1)->minute();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE, [
            'HTTP_USER_AGENT' => 'another_useragent_to_be_identified_as_new_device',
        ])->assertOk();

        Notification::assertSentTo($this->user, SignedInWithNewDeviceNotification::class);
    }

    #[Test]
    public function test_webauthn_login_does_not_send_new_device_notification_to_new_user()
    {
        Notification::fake();

        $this->user['preferences->notifyOnNewAuthDevice'] = 1;
        $this->user->save();

        $this->createWebauthnCredential(self::CREDENTIAL_ID_ALT, $this->user->id, self::USER_ID_ALT);
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk();

        Notification::assertNothingSentTo($this->user);
    }

    #[Test]
    public function test_webauthn_login_does_not_send_new_device_notification_if_user_disabled_it()
    {
        Notification::fake();

        $this->user['preferences->notifyOnNewAuthDevice'] = 0;
        $this->user->save();

        $this->createWebauthnCredential(self::CREDENTIAL_ID_ALT, $this->user->id, self::USER_ID_ALT);
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk();

        Notification::assertNothingSentTo($this->user);
    }

    #[Test]
    public function test_webauthn_admin_login_returns_admin_role()
    {
        DB::table('users')->delete();
        $this->user = User::factory()->administrator()->create(['email' => self::EMAIL]);

        $this->createWebauthnCredential(self::CREDENTIAL_ID_ALT, $this->user->id, self::USER_ID_ALT);
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk()
            ->assertJsonFragment([
                'is_admin' => true,
            ]);
    }

    #[Test]
    public function test_webauthn_login_merge_handle_if_missing()
    {
        $this->createWebauthnCredential(self::CREDENTIAL_ID_ALT, $this->user->id, self::USER_ID_ALT);
        $this->addWebauthnChallengeToSession();

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

    #[Test]
    public function test_legacy_login_is_rejected_when_webauthn_only_is_enable()
    {
        // Set to webauthn only
        $this->user['preferences->useWebauthnOnly'] = true;
        $this->user->save();

        $response = $this->json('POST', '/user/login', [
            'email'    => self::EMAIL,
            'password' => 'password',
        ])
            ->assertUnauthorized();
    }

    #[Test]
    public function test_webauthn_login_already_authenticated_is_rejected()
    {
        $this->createWebauthnCredential(self::CREDENTIAL_ID_ALT, $this->user->id, self::USER_ID_ALT);
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_webauthn_login_with_missing_data_returns_validation_error()
    {
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

    #[Test]
    public function test_webauthn_invalid_login_returns_unauthorized()
    {
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_INVALID)
            ->assertUnauthorized();
    }

    #[Test]
    public function test_too_many_invalid_login_attempts_returns_too_many_request_error()
    {
        $throttle = 8;
        Config::set('auth.throttle.login', $throttle);

        $this->addWebauthnChallengeToSession();

        for ($i = 0; $i < $throttle - 1; $i++) {
            $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_INVALID);
        }

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_INVALID)
            ->assertUnauthorized();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_INVALID)
            ->assertStatus(429);
    }

    #[Test]
    public function test_get_options_returns_success()
    {
        Config::set('webauthn.user_verification', UserVerification::PREFERRED);

        $this->createWebauthnCredential(self::CREDENTIAL_ID, $this->user->id, self::USER_ID);

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

    #[Test]
    public function test_get_options_for_securelogin_returns_required_userVerification()
    {
        Config::set('webauthn.user_verification', UserVerification::REQUIRED);

        $this->createWebauthnCredential(self::CREDENTIAL_ID, $this->user->id, self::USER_ID);

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

    #[Test]
    public function test_get_options_for_fastlogin_returns_discouraged_userVerification()
    {
        Config::set('webauthn.user_verification', UserVerification::DISCOURAGED);

        $this->createWebauthnCredential(self::CREDENTIAL_ID, $this->user->id, self::USER_ID);

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

    #[Test]
    public function test_get_options_with_capitalized_email_returns_success()
    {
        $this->json('POST', '/webauthn/login/options', [
            'email' => strtoupper($this->user->email),
        ])
            ->assertOk();
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function test_successful_webauthn_login_is_logged()
    {
        $this->createWebauthnCredential(self::CREDENTIAL_ID_ALT, $this->user->id, self::USER_ID_ALT);
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE)
            ->assertOk();

        $this->assertDatabaseHas('auth_logs', [
            'authenticatable_id' => $this->user->id,
            'login_successful'   => true,
            'guard'              => self::GUARD,
            'login_method'       => self::AUTH_METHOD,
            'logout_at'          => null,
        ]);
    }

    #[Test]
    public function test_failed_webauthn_login_is_not_logged()
    {
        $this->addWebauthnChallengeToSession();

        $this->json('POST', '/webauthn/login', self::ASSERTION_RESPONSE_INVALID)
            ->assertUnauthorized();

        // When webauthn fails, the fireFailedEvent() of the sessionGuard returns
        // a null $user so nothing should be logged
        $this->assertDatabaseMissing('auth_logs', [
            'authenticatable_id' => $this->user->id,
            'guard'              => self::GUARD,
            'login_method'       => self::AUTH_METHOD,
        ]);
    }

    /**
     * Set a session
     */
    protected function addWebauthnChallengeToSession() : void
    {
        $this->session(['_webauthn' => new \Laragear\WebAuthn\Challenge(
            new \Laragear\WebAuthn\ByteBuffer(base64_decode(self::ASSERTION_CHALLENGE)),
            60,
            false,
        )]);
    }

    /**
     * Inserts a webauthn credential in database
     */
    protected function createWebauthnCredential(string $credentialId, int $authenticatableId, string $userId) : void
    {
        DB::table('webauthn_credentials')->insert([
            'id'                   => $credentialId,
            'authenticatable_type' => \App\Models\User::class,
            'authenticatable_id'   => $authenticatableId,
            'user_id'              => $userId,
            'counter'              => 0,
            'rp_id'                => 'http://localhost',
            'origin'               => 'http://localhost',
            'aaguid'               => '00000000-0000-0000-0000-000000000000',
            'attestation_format'   => 'none',
            'public_key'           => self::PUBLIC_KEY,
            'updated_at'           => now(),
            'created_at'           => now(),
        ]);
    }
}
