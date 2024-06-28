<?php

namespace Tests\Feature\Http\Auth;

use App\Exceptions\Handler;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\KickOutInactiveUser;
use App\Http\Middleware\LogUserLastSeen;
use App\Http\Middleware\RejectIfAuthenticated;
use App\Http\Middleware\RejectIfDemoMode;
use App\Http\Middleware\RejectIfReverseProxy;
use App\Http\Middleware\SkipIfAuthenticated;
use App\Listeners\Authentication\FailedLoginListener;
use App\Listeners\Authentication\LoginListener;
use App\Listeners\Authentication\LogoutListener;
use App\Listeners\LogNotificationListener;
use App\Models\AuthLog;
use App\Models\User;
use App\Notifications\FailedLoginNotification;
use App\Notifications\SignedInWithNewDeviceNotification;
use App\Rules\CaseInsensitiveEmailExists;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * LoginTest test class
 */
#[CoversClass(LoginController::class)]
#[CoversClass(RejectIfAuthenticated::class)]
#[CoversClass(RejectIfReverseProxy::class)]
#[CoversClass(RejectIfDemoMode::class)]
#[CoversClass(LoginListener::class)]
#[CoversClass(LogoutListener::class)]
#[CoversClass(FailedLoginListener::class)]
#[CoversMethod(CaseInsensitiveEmailExists::class, 'validate')]
#[CoversMethod(SkipIfAuthenticated::class, 'handle')]
#[CoversMethod(Handler::class, 'register')]
#[CoversMethod(KickOutInactiveUser::class, 'handle')]
#[CoversMethod(LogUserLastSeen::class, 'handle')]
#[CoversClass(LogNotificationListener::class)]
#[CoversClass(SignedInWithNewDeviceNotification::class)]
#[CoversClass(FailedLoginNotification::class)]
class LoginTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $admin;

    private const WEB_GUARD = 'web-guard';

    private const PASSWORD = 'password';

    private const WRONG_PASSWORD = 'wrong_password';

    public function setUp() : void
    {
        parent::setUp();

        $this->user  = User::factory()->create();
        $this->admin = User::factory()->administrator()->create();
    }

    #[Test]
    public function test_user_login_returns_success()
    {
        Notification::fake();

        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ])
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
    public function test_login_send_new_device_notification()
    {
        Notification::fake();

        $this->user['preferences->notifyOnNewAuthDevice'] = 1;
        $this->user->save();

        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ])->assertOk();

        $this->actingAs($this->user, self::WEB_GUARD)
            ->json('GET', '/user/logout');

        $this->travel(1)->minute();

        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ], [
            'HTTP_USER_AGENT' => 'NotSymfony',
        ])->assertOk();

        Notification::assertSentTo($this->user, SignedInWithNewDeviceNotification::class);
    }

    #[Test]
    public function test_login_does_not_send_new_device_notification()
    {
        Notification::fake();

        $this->user['preferences->notifyOnNewAuthDevice'] = 0;
        $this->user->save();

        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ])->assertOk();

        $this->actingAs($this->user, self::WEB_GUARD)
            ->json('GET', '/user/logout');

        $this->travel(1)->minute();

        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ], [
            'HTTP_USER_AGENT' => 'NotSymfony',
        ])->assertOk();

        Notification::assertNothingSentTo($this->user);
    }

    #[Test]
    public function test_admin_login_returns_admin_role()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => $this->admin->email,
            'password' => self::PASSWORD,
        ])
            ->assertOk()
            ->assertJsonFragment([
                'is_admin' => true,
            ]);
    }

    #[Test]
    public function test_user_login_with_uppercased_email_returns_success()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => strtoupper($this->user->email),
            'password' => self::PASSWORD,
        ])
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
    public function test_successful_web_login_with_password_is_logged()
    {
        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ])->assertOk();

        $this->assertDatabaseHas('auth_logs', [
            'authenticatable_id' => $this->user->id,
            'login_successful'   => true,
            'guard'              => self::WEB_GUARD,
            'login_method'       => self::PASSWORD,
            'logout_at'          => null,
        ]);
    }

    #[Test]
    public function test_failed_web_login_with_password_is_logged()
    {
        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ])->assertStatus(401);

        $this->assertDatabaseHas('auth_logs', [
            'authenticatable_id' => $this->user->id,
            'login_successful'   => false,
            'guard'              => self::WEB_GUARD,
            'login_method'       => self::PASSWORD,
            'logout_at'          => null,
        ]);
    }

    #[Test]
    public function test_user_login_already_authenticated_is_rejected()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ]);

        $response = $this->actingAs($this->user, self::WEB_GUARD)
            ->json('POST', '/user/login', [
                'email'    => $this->user->email,
                'password' => self::PASSWORD,
            ])
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_user_login_with_missing_data_returns_validation_error()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => '',
            'password' => '',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
                'password',
            ]);
    }

    #[Test]
    public function test_user_login_with_invalid_credentials_returns_unauthorized()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ])
            ->assertStatus(401)
            ->assertJson([
                'message' => 'unauthorized',
            ]);
    }

    #[Test]
    public function test_login_with_invalid_credentials_send_failed_login_notification()
    {
        Notification::fake();

        $this->user['preferences->notifyOnFailedLogin'] = 1;
        $this->user->save();

        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ])->assertStatus(401);

        Notification::assertSentTo($this->user, FailedLoginNotification::class);
    }

    #[Test]
    public function test_login_with_invalid_credentials_does_not_send_new_device_notification()
    {
        Notification::fake();

        $this->user['preferences->notifyOnFailedLogin'] = 0;
        $this->user->save();

        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ])->assertStatus(401);

        Notification::assertNothingSentTo($this->user);
    }

    #[Test]
    public function test_too_many_login_attempts_with_invalid_credentials_returns_too_many_request_error()
    {
        $throttle = 8;
        Config::set('auth.throttle.login', $throttle);

        $post = [
            'email'    => $this->user->email,
            'password' => self::WRONG_PASSWORD,
        ];

        for ($i = 0; $i < $throttle - 1; $i++) {
            $this->json('POST', '/user/login', $post);
        }

        $this->json('POST', '/user/login', $post)
            ->assertUnauthorized();

        $this->json('POST', '/user/login', $post)
            ->assertStatus(429);
    }

    #[Test]
    public function test_user_logout_returns_validation_success()
    {
        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ]);

        $response = $this->actingAs($this->user, self::WEB_GUARD)
            ->json('GET', '/user/logout')
            ->assertOk()
            ->assertExactJson([
                'message' => 'signed out',
            ]);
    }

    #[Test]
    public function test_user_logout_after_inactivity_returns_teapot()
    {
        // Set the autolock period to 1 minute
        $this->user['preferences->kickUserAfter'] = 1;
        $this->user->save();

        $response = $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ]);

        // Ping a protected endpoint to log last_seen_at time
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts');

        $this->travelTo(Carbon::now()->addMinutes(2));

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/twofaccounts')
            ->assertStatus(418);
    }

    #[Test]
    public function test_successful_web_logout_is_logged()
    {
        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ])->assertOk();

        $this->actingAs($this->user, self::WEB_GUARD)
            ->json('GET', '/user/logout')
            ->assertOk();

        $authlog = AuthLog::first();

        $this->assertEquals($this->user->id, $authlog->authenticatable_id);
        $this->assertTrue($authlog->login_successful);
        $this->assertEquals(self::WEB_GUARD, $authlog->guard);
        $this->assertEquals(self::PASSWORD, $authlog->login_method);
        $this->assertNotNull($authlog->logout_at);
    }

    #[Test]
    public function test_orphan_web_logout_is_logged()
    {
        $this->actingAs($this->user, self::WEB_GUARD)
            ->json('GET', '/user/logout')
            ->assertOk();

        $authlog = AuthLog::first();

        $this->assertEquals($this->user->id, $authlog->authenticatable_id);
        $this->assertFalse($authlog->login_successful);
        $this->assertEquals(self::WEB_GUARD, $authlog->guard);
        $this->assertNull($authlog->login_method);
        $this->assertNotNull($authlog->logout_at);
    }
}
