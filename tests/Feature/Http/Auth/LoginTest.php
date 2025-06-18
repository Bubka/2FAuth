<?php

namespace Tests\Feature\Http\Auth;

use App\Exceptions\Handler;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\KickOutInactiveUser;
use App\Http\Middleware\LogUserLastSeen;
use App\Http\Middleware\RejectIfAuthenticated;
use App\Http\Middleware\RejectIfDemoMode;
use App\Http\Middleware\RejectIfReverseProxy;
use App\Listeners\Authentication\FailedLoginListener;
use App\Listeners\Authentication\LoginListener;
use App\Listeners\Authentication\LogoutListener;
use App\Listeners\Authentication\VisitedByProxyUserListener;
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
#[CoversClass(VisitedByProxyUserListener::class)]
#[CoversMethod(CaseInsensitiveEmailExists::class, 'validate')]
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

    private const REVERSE_PROXY_GUARD = 'reverse-proxy-guard';

    private const PASSWORD = 'password';

    private const WRONG_PASSWORD = 'wrong_password';

    private const USER_NAME = 'John';

    private const USER_EMAIL = 'john@example.com';

    protected function setUp() : void
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
            'HTTP_USER_AGENT' => 'another_useragent_to_be_identified_as_new_device',
        ])->assertOk();

        Notification::assertSentTo($this->user, SignedInWithNewDeviceNotification::class);
    }

    #[Test]
    public function test_login_does_not_send_new_device_notification_if_user_disabled_it()
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
            'HTTP_USER_AGENT' => 'another_useragent_to_be_identified_as_new_device',
        ])->assertOk();

        Notification::assertNothingSentTo($this->user);
    }

    #[Test]
    public function test_login_does_not_send_new_device_notification_if_user_is_considered_new()
    {
        Notification::fake();

        $this->user['preferences->notifyOnNewAuthDevice'] = 1;
        $this->user->save();

        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
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
            ->assertStatus(200);
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

        $authlog = $this->user->latestAuthentication()->first();

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

    #[Test]
    public function test_reverse_proxy_access_is_logged()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $user = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);

        $this->app['auth']->shouldUse(self::REVERSE_PROXY_GUARD);

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ]);

        $this->assertDatabaseHas('auth_logs', [
            'authenticatable_id' => $user->id,
            'login_successful'   => true,
            'guard'              => self::REVERSE_PROXY_GUARD,
            'login_method'       => null,
            'logout_at'          => null,
        ]);
    }

    #[Test]
    public function test_reverse_proxy_access_is_logged_only_once_during_a_quarter()
    {
        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $user = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);

        $this->app['auth']->shouldUse(self::REVERSE_PROXY_GUARD);

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ]);

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ]);

        $this->assertDatabaseCount('auth_logs', 1);

        $this->travel(16)->minutes();

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ]);

        $this->assertDatabaseCount('auth_logs', 2);
    }

    #[Test]
    public function test_reverse_proxy_access_sends_new_device_notification()
    {
        Notification::fake();

        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $user = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);
        $user['preferences->notifyOnNewAuthDevice'] = true;
        $user->save();
        $user->refresh();

        $this->app['auth']->shouldUse(self::REVERSE_PROXY_GUARD);

        // We travel back for 2 minutes to avoid the user being considered as a new user
        $this->travelTo(Carbon::now()->subMinutes(2));

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ]);

        Notification::assertSentTo($user, SignedInWithNewDeviceNotification::class);
    }

    #[Test]
    public function test_reverse_proxy_access_does_not_send_new_device_notification_if_user_disabled_it()
    {
        Notification::fake();

        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $user = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);
        $user['preferences->notifyOnNewAuthDevice'] = false;
        $user->save();
        $user->refresh();

        $this->app['auth']->shouldUse(self::REVERSE_PROXY_GUARD);

        // We travel back for 2 minutes to avoid the user being considered as a new user
        $this->travelTo(Carbon::now()->subMinutes(2));

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ]);

        Notification::assertNothingSentTo($user);
    }

    #[Test]
    public function test_reverse_proxy_does_not_send_new_device_notification_if_user_is_considered_new()
    {
        Notification::fake();

        Config::set('auth.auth_proxy_headers.user', 'HTTP_REMOTE_USER');

        $user = User::factory()->create([
            'name'  => self::USER_NAME,
            'email' => strtolower(self::USER_NAME) . '@remote',
        ]);
        $user['preferences->notifyOnNewAuthDevice'] = true;
        $user->save();
        $user->refresh();

        $this->app['auth']->shouldUse(self::REVERSE_PROXY_GUARD);

        $this->json('GET', '/api/v1/groups', [], [
            'HTTP_REMOTE_USER' => self::USER_NAME,
        ]);

        Notification::assertNothingSentTo($user);
    }
}
