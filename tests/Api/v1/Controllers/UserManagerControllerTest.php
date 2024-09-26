<?php

namespace Tests\Api\v1\Controllers;

use App\Api\v1\Controllers\UserManagerController;
use App\Api\v1\Resources\UserAuthenticationResource;
use App\Api\v1\Resources\UserManagerResource;
use App\Models\AuthLog;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Observers\UserObserver;
use App\Policies\UserPolicy;
use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Passport\TokenRepository;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(UserManagerController::class)]
#[CoversClass(UserManagerResource::class)]
#[CoversClass(UserAuthenticationResource::class)]
#[CoversClass(User::class)]
#[CoversClass(UserObserver::class)]
#[CoversClass(UserPolicy::class)]
class UserManagerControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $admin;

    protected $user;

    protected $anotherUser;

    /**
     * @var array
     */
    protected $defaultPreferences;

    private const USERNAME = 'john doe';

    private const EMAIL = 'johndoe@example.org';

    private const PASSWORD = 'password';

    public function setUp() : void
    {
        parent::setUp();

        $this->admin       = User::factory()->administrator()->create();
        $this->user        = User::factory()->create();
        $this->anotherUser = User::factory()->create();
        TwoFAccount::factory()->for($this->anotherUser)->create();

        foreach (config('2fauth.preferences') as $pref => $value) {
            $this->defaultPreferences[$pref] = config('2fauth.preferences.' . $pref);
        }
    }

    #[Test]
    public function test_all_controller_routes_are_protected_by_admin_middleware()
    {
        $routes = Route::getRoutes()->getRoutes();

        $controllerRoutes = Arr::where($routes, function (\Illuminate\Routing\Route $route, int $key) {
            if (Str::startsWith($route->getActionName(), UserManagerController::class)) {
                return $route;
            }
        });

        foreach ($controllerRoutes as $controllerRoute) {
            $this->assertContains('admin', $controllerRoute->middleware());
        }
    }

    #[Test]
    public function test_index_returns_all_users_with_expected_UserManagerResources() : void
    {
        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users')
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'last_seen_at',
                    'created_at',
                ],
            ])
            ->assertJsonFragment([
                'id'                 => $this->user->id,
                'name'               => $this->user->name,
                'email'              => $this->user->email,
                'oauth_provider'     => null,
                'preferences'        => $this->defaultPreferences,
                'is_admin'           => false,
                'twofaccounts_count' => 0,
            ])
            ->assertJsonFragment([
                'id'                 => $this->admin->id,
                'name'               => $this->admin->name,
                'email'              => $this->admin->email,
                'oauth_provider'     => null,
                'preferences'        => $this->defaultPreferences,
                'is_admin'           => true,
                'twofaccounts_count' => 0,
            ])
            ->assertJsonFragment([
                'id'                 => $this->anotherUser->id,
                'name'               => $this->anotherUser->name,
                'email'              => $this->anotherUser->email,
                'oauth_provider'     => null,
                'preferences'        => $this->defaultPreferences,
                'is_admin'           => false,
                'twofaccounts_count' => 1,
            ]);
    }

    #[Test]
    public function test_show_returns_the_expected_UserManagerResource() : void
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id)
            ->assertJson([
                'info' => [
                    'id'                 => $this->user->id,
                    'name'               => $this->user->name,
                    'email'              => $this->user->email,
                    'oauth_provider'     => null,
                    'preferences'        => $this->defaultPreferences,
                    'is_admin'           => false,
                    'twofaccounts_count' => 0,
                    'last_seen_at'       => '0 seconds ago',
                    'created_at'         => '0 seconds ago',
                ],
                'password_reset'               => null,
                'valid_personal_access_tokens' => 0,
                'webauthn_credentials'         => 0,
            ]);
    }

    #[Test]
    public function test_show_returns_forbidden_to_non_admin_user() : void
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->anotherUser->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_resetPassword_resets_password_and_sends_password_reset_to_user()
    {
        Notification::fake();

        DB::table(config('auth.passwords.users.table'))->delete();
        $user        = User::factory()->create();
        $oldPassword = $user->password;

        $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', '/api/v1/users/' . $user->id . '/password/reset')
            ->assertOk();

        $user->refresh();
        $this->assertNotEquals($oldPassword, $user->password);

        $resetToken = DB::table(config('auth.passwords.users.table'))->first();

        $this->assertNotNull($resetToken);
        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($resetToken) {
            return Hash::check($notification->token, $resetToken->token) === true;
        });
    }

    #[Test]
    public function test_resetPassword_returns_UserManagerResource()
    {
        Notification::fake();

        $user    = User::factory()->create();
        $path    = '/api/v1/users/' . $user->id . '/password/reset';
        $request = Request::create($path, 'PATCH');

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', $path);
        $resources = UserManagerResource::make($user);

        $response->assertExactJson($resources->response($request)->getData(true));
    }

    #[Test]
    public function test_resetPassword_does_not_notify_when_reset_failed_and_returns_error()
    {
        Notification::fake();

        $broker = $this->partialMock(\Illuminate\Auth\Passwords\PasswordBroker::class, function (MockInterface $broker) {
            $broker->shouldReceive('createToken')
                ->andReturn('fakeValue');
            $broker->shouldReceive('reset')
                ->andReturn(false);
        });

        Password::shouldReceive('broker')->andReturn($broker);

        $user = User::factory()->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', '/api/v1/users/' . $user->id . '/password/reset')
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);

        Notification::assertNothingSent();
    }

    #[Test]
    public function test_resetPassword_returns_error_when_notify_send_failed()
    {
        Notification::fake();

        $broker = $this->partialMock(\Illuminate\Auth\Passwords\PasswordBroker::class, function (MockInterface $broker) {
            $broker->shouldReceive('createToken')
                ->andReturn('fakeValue');
            $broker->shouldReceive('reset')
                ->andReturn(Password::PASSWORD_RESET);
            $broker->shouldReceive('sendResetLink')
                ->andReturn('badValue');
        });

        Password::shouldReceive('broker')->andReturn($broker);

        $user = User::factory()->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', '/api/v1/users/' . $user->id . '/password/reset')
            ->assertStatus(400)
            ->assertJsonStructure([
                'message',
                'reason',
            ]);

        Notification::assertNothingSent();
    }

    #[Test]
    public function test_store_creates_the_user_and_returns_success()
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('POST', '/api/v1/users', [
                'name'                  => self::USERNAME,
                'email'                 => self::EMAIL,
                'password'              => self::PASSWORD,
                'password_confirmation' => self::PASSWORD,
                'is_admin'              => false,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('users', [
            'name'  => self::USERNAME,
            'email' => self::EMAIL,
        ]);
    }

    #[Test]
    public function test_store_returns_UserManagerResource_of_created_user() : void
    {
        $path                                    = '/api/v1/users';
        $userDefinition                          = (new UserFactory)->definition();
        $userDefinition['password_confirmation'] = $userDefinition['password'];
        $request                                 = Request::create($path, 'POST');

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('POST', $path, $userDefinition)
            ->assertCreated();

        $user     = User::where('email', $userDefinition['email'])->first();
        $resource = UserManagerResource::make($user);

        $response->assertExactJson($resource->response($request)->getData(true));
    }

    #[Test]
    public function test_store_returns_UserManagerResource_of_created_admin() : void
    {
        $path                                    = '/api/v1/users';
        $userDefinition                          = (new UserFactory)->definition();
        $userDefinition['is_admin']              = true;
        $userDefinition['password_confirmation'] = $userDefinition['password'];
        $request                                 = Request::create($path, 'POST');

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('POST', $path, $userDefinition)
            ->assertCreated();

        $user     = User::where('email', $userDefinition['email'])->first();
        $resource = UserManagerResource::make($user);

        $response->assertExactJson($resource->response($request)->getData(true));
    }

    #[Test]
    public function test_store_another_user_returns_forbidden() : void
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/users', [
                'name'                  => self::USERNAME,
                'email'                 => self::EMAIL,
                'password'              => self::PASSWORD,
                'password_confirmation' => self::PASSWORD,
                'is_admin'              => false,
            ])
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_revokePATs_flushes_pats()
    {
        Artisan::call('passport:install', [
            '--verbose'        => 2,
            '--no-interaction' => 1,
        ]);

        $tokenRepository = app(TokenRepository::class);

        $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/oauth/personal-access-tokens', [
                'name' => 'RandomTokenName',
            ])
            ->assertOk();

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->user->id . '/pats');

        $tokens = $tokenRepository->forUser($this->user->getAuthIdentifier());
        $tokens = $tokens->load('client')->filter(function ($token) {
            return $token->client->personal_access_client && ! $token->revoked;
        });

        $this->assertCount(0, $tokens);
    }

    #[Test]
    public function test_revokePATs_returns_no_content()
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->user->id . '/pats')
            ->assertNoContent();
    }

    #[Test]
    public function test_revokePATs_always_returns_no_content()
    {
        // a fresh user has no token
        $user = User::factory()->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $user->id . '/pats')
            ->assertNoContent();
    }

    #[Test]
    public function test_revokeWebauthnCredentials_flushes_credentials()
    {
        DB::table('webauthn_credentials')->insert([
            'id'                   => '-VOLFKPY-_FuMI_sJ7gMllK76L3VoRUINj6lL_Z3qDg',
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

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->user->id . '/credentials');

        $this->assertDatabaseMissing('webauthn_credentials', [
            'authenticatable_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function test_revokeWebauthnCredentials_returns_no_content()
    {
        DB::table('webauthn_credentials')->insert([
            'id'                   => '-VOLFKPY-_FuMI_sJ7gMllK76L3VoRUINj6lL_Z3qDg',
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

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->user->id . '/credentials')
            ->assertNoContent();
    }

    #[Test]
    public function test_revokeWebauthnCredentials_always_returns_no_content()
    {
        DB::table('webauthn_credentials')->delete();

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->user->id . '/credentials')
            ->assertNoContent();
    }

    #[Test]
    public function test_revokeWebauthnCredentials_resets_useWebauthnOnly_user_preference()
    {
        $this->user['preferences->useWebauthnOnly'] = true;
        $this->user->save();

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->user->id . '/credentials')
            ->assertNoContent();

        $this->user->refresh();

        $this->assertFalse($this->user->preferences['useWebauthnOnly']);
    }

    #[Test]
    public function test_destroy_returns_no_content()
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $user->id)
            ->assertNoContent();
    }

    #[Test]
    public function test_destroy_the_only_admin_returns_forbidden()
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->admin->id)
            ->assertForbidden();
    }

    #[Test]
    public function test_destroy_another_user_returns_forbidden() : void
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->anotherUser->id)
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_promote_changes_admin_status() : void
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', '/api/v1/users/' . $this->user->id . '/promote', [
                'is_admin' => true,
            ])
            ->assertOk();

        $this->user->refresh();

        $this->assertTrue($this->user->isAdministrator());
    }

    #[Test]
    public function test_promote_returns_UserManagerResource() : void
    {
        $path    = '/api/v1/users/' . $this->user->id . '/promote';
        $request = Request::create($path, 'PUT');

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', $path, [
                'is_admin' => true,
            ]);

        $this->user->refresh();
        $resources = UserManagerResource::make($this->user);

        $response->assertExactJson($resources->response($request)->getData(true));
    }

    #[Test]
    public function test_promote_another_user_returns_forbidden() : void
    {
        $this->actingAs($this->user, 'api-guard')
            ->json('PATCH', '/api/v1/users/' . $this->anotherUser->id . '/promote', [
                'is_admin' => true,
            ])
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }

    #[Test]
    public function test_demote_returns_UserManagerResource() : void
    {
        $anotherAdmin = User::factory()->administrator()->create();

        $path    = '/api/v1/users/' . $anotherAdmin->id . '/promote';
        $request = Request::create($path, 'PUT');

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', $path, [
                'is_admin' => false,
            ]);

        $anotherAdmin->refresh();
        $resources = UserManagerResource::make($anotherAdmin);

        $response->assertExactJson($resources->response($request)->getData(true));
    }

    #[Test]
    public function test_demote_the_only_admin_returns_forbidden() : void
    {
        $this->assertTrue(User::admins()->count() == 1);

        $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', '/api/v1/users/' . $this->admin->id . '/promote', [
                'is_admin' => false,
            ])
            ->assertForbidden();
    }

    #[Test]
    public function test_authentications_returns_all_preserved_entries() : void
    {
        AuthLog::factory()->for($this->user, 'authenticatable')->duringLastYear()->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->duringLastSixMonth()->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->duringLastThreeMonth()->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->duringLastMonth()->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->logoutOnly()->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->failedLogin()->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications')
            ->assertOk()
            ->assertJsonCount(7);
    }

    #[Test]
    public function test_authentications_does_not_return_old_entries() : void
    {
        AuthLog::factory()->for($this->user, 'authenticatable')->beforeLastYear()->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications')
            ->assertOk()
            ->assertJsonCount(0);
    }

    #[Test]
    public function test_authentications_returns_user_entries_only() : void
    {
        AuthLog::factory()->for($this->admin, 'authenticatable')->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->create();

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications')
            ->assertJsonCount(1);

        $userAuthLog = AuthLog::find($response->getData()[0]->id);
        $this->assertEquals($userAuthLog->authenticatable_id, $this->user->id);
    }

    #[Test]
    public function test_authentications_returns_expected_resource() : void
    {
        AuthLog::factory()->for($this->user, 'authenticatable')->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications')
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'ip_address',
                    'user_agent',
                    'browser',
                    'platform',
                    'device',
                    'login_at',
                    'logout_at',
                    'login_successful',
                    'duration',
                    'login_method',
                ],
            ]);
    }

    #[Test]
    public function test_authentications_returns_resource_with_timezoned_dates() : void
    {
        $timezone                             = 'Europe/Paris';
        $this->admin['preferences->timezone'] = $timezone;
        $this->admin->save();

        $now          = now();
        $timezonedNow = now($timezone);

        AuthLog::factory()->for($this->user, 'authenticatable')->create([
            'login_at'  => $now,
            'logout_at' => $now,
        ]);

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications');

        $login_at  = Carbon::parse($response->getData()[0]->login_at);
        $logout_at = Carbon::parse($response->getData()[0]->logout_at);

        $this->assertTrue($login_at->isSameHour($timezonedNow));
        $this->assertTrue($login_at->isSameMinute($timezonedNow));
        $this->assertTrue($logout_at->isSameHour($timezonedNow));
        $this->assertTrue($logout_at->isSameMinute($timezonedNow));
    }

    #[Test]
    public function test_authentications_returns_loginless_entries() : void
    {
        $this->logUserOut();

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications')
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'login_at' => null,
            ]);
    }

    #[Test]
    public function test_authentications_returns_logoutless_entries() : void
    {
        $this->logUserIn();

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications')
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'logout_at' => null,
            ]);
    }

    #[Test]
    public function test_authentications_returns_failed_entry() : void
    {
        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => 'wrong_password',
        ]);

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications?period=1')
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'login_successful' => false,
            ]);
    }

    #[Test]
    public function test_authentications_returns_last_month_entries() : void
    {
        $this->travel(-2)->months();
        $this->logUserInAndOut();
        $this->travelBack();
        $this->logUserIn();

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications?period=1')
            ->assertJsonCount(1);

        $this->assertTrue(Carbon::parse($response->getData()[0]->login_at)->isSameDay(now()));
    }

    #[Test]
    public function test_authentications_returns_last_three_months_entries() : void
    {
        $this->travel(-100)->days();
        $this->logUserInAndOut();
        $this->travelBack();
        $this->travel(-80)->days();
        $this->logUserIn();
        $this->travelBack();

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications?period=3')
            ->assertJsonCount(1);

        $this->assertTrue(Carbon::parse($response->getData()[0]->login_at)->isSameDay(now()->subDays(80)));
    }

    #[Test]
    public function test_authentications_returns_last_six_months_entries() : void
    {
        $this->travel(-7)->months();
        $this->logUserInAndOut();
        $this->travelBack();
        $this->travel(-5)->months();
        $this->logUserIn();
        $this->travelBack();

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications?period=6')
            ->assertJsonCount(1);

        $this->assertTrue(Carbon::parse($response->getData()[0]->login_at)->isSameDay(now()->subMonths(5)));
    }

    #[Test]
    public function test_authentications_returns_last_year_entries() : void
    {
        $this->travel(-13)->months();
        $this->logUserInAndOut();
        $this->travelBack();
        $this->travel(-11)->months();
        $this->logUserIn();
        $this->travelBack();

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications?period=12')
            ->assertJsonCount(1);

        $this->assertTrue(Carbon::parse($response->getData()[0]->login_at)->isSameDay(now()->subMonths(11)));
    }

    #[Test]
    #[DataProvider('LimitProvider')]
    public function test_authentications_returns_limited_entries($limit) : void
    {
        AuthLog::factory()->for($this->user, 'authenticatable')->duringLastYear()->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->duringLastSixMonth()->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->duringLastThreeMonth()->create();
        AuthLog::factory()->for($this->user, 'authenticatable')->duringLastMonth()->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications?limit=' . $limit)
            ->assertOk()
            ->assertJsonCount($limit);
    }

    /**
     * Provide various limits
     */
    public static function LimitProvider()
    {
        return [
            'limited to 1' => [1],
            'limited to 2' => [2],
            'limited to 3' => [3],
            'limited to 4' => [4],
        ];
    }

    #[Test]
    public function test_authentications_returns_expected_ip_and_useragent_chunks() : void
    {
        AuthLog::factory()->for($this->user, 'authenticatable')->create([
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/115.0',
        ]);

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications?period=1')
            ->assertJsonFragment([
                'ip_address' => '127.0.0.1',
                'browser'    => 'Firefox',
                'platform'   => 'Windows',
                'device'     => 'desktop',
            ]);
    }

    #[Test]
    #[DataProvider('invalidQueryParameterProvider')]
    public function test_authentications_with_invalid_limit_returns_validation_error($limit) : void
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications?limit=' . $limit)
            ->assertInvalid(['limit']);
    }

    #[Test]
    #[DataProvider('invalidQueryParameterProvider')]
    public function test_authentications_with_invalid_period_returns_validation_error($period) : void
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id . '/authentications?period=' . $period)
            ->assertInvalid(['period']);
    }

    /**
     * Provide various invalid value to test query parameter
     */
    public static function invalidQueryParameterProvider()
    {
        return [
            'empty'   => [''],
            'null'    => ['null'],
            'boolean' => ['true'],
            'string'  => ['string'],
            'array'   => ['[]'],
        ];
    }

    /**
     * Makes a request to login the user in
     */
    protected function logUserIn() : void
    {
        $this->json('POST', '/user/login', [
            'email'    => $this->user->email,
            'password' => self::PASSWORD,
        ]);
    }

    /**
     * Makes a request to login the user out
     */
    protected function logUserOut() : void
    {
        $this->actingAs($this->user, 'web-guard')
            ->json('GET', '/user/logout');
    }

    /**
     * Makes a request to login the user out
     */
    protected function logUserInAndOut() : void
    {
        $this->logUserIn();
        $this->logUserOut();
    }
}
