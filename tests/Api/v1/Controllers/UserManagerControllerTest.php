<?php

namespace Tests\Api\v1\Controllers;

use App\Api\v1\Controllers\UserManagerController;
use App\Api\v1\Resources\UserManagerResource;
use App\Models\User;
use App\Policies\UserPolicy;
use Database\Factories\UserFactory;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Passport\TokenRepository;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\FeatureTestCase;

#[CoversClass(UserManagerController::class)]
#[CoversClass(UserManagerResource::class)]
#[CoversClass(UserPolicy::class)]
#[CoversClass(User::class)]
class UserManagerControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $admin;

    protected $user;

    protected $anotherUser;

    private const USERNAME = 'john doe';

    private const EMAIL = 'johndoe@example.org';

    private const PASSWORD = 'password';

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->admin       = User::factory()->administrator()->create();
        $this->user        = User::factory()->create();
        $this->anotherUser = User::factory()->create();
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function test_index_returns_all_users()
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users')
            ->assertOk()
            ->assertJsonCount(3)
            ->assertJsonFragment([
                'email' => $this->admin->email,
            ])
            ->assertJsonFragment([
                'email' => $this->user->email,
            ])
            ->assertJsonFragment([
                'email' => $this->anotherUser->email,
            ]);
    }

    /**
     * @test
     */
    public function test_index_succeeds_and_returns_UserManagerResource(): void
    {
        $path = '/api/v1/users';
        $resources = UserManagerResource::collection(User::all());
        $request  = Request::create($path, 'GET');

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', $path)
            ->assertExactJson($resources->response($request)->getData(true));
    }

    /**
     * @test
     */
    public function test_show_returns_the_correct_user()
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', '/api/v1/users/' . $this->user->id)
            ->assertJsonFragment([
                'email' => $this->user->email,
            ]);
    }

    /**
     * @test
     */
    public function test_show_returns_UserManagerResource(): void
    {
        $path = '/api/v1/users/' . $this->user->id;
        $resources = UserManagerResource::make($this->user);
        $request  = Request::create($path, 'GET');

        $this->actingAs($this->admin, 'api-guard')
            ->json('GET', $path)
            ->assertExactJson($resources->response($request)->getData(true));
    }

    /**
     * @test
     */
    public function test_resetPassword_resets_password_and_sends_password_reset_to_user()
    {
        Notification::fake();

        DB::table(config('auth.passwords.users.table'))->delete();
        $user  = User::factory()->create();
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

    /**
     * @test
     */
    public function test_resetPassword_returns_UserManagerResource()
    {
        Notification::fake();

        $user  = User::factory()->create();
        $path = '/api/v1/users/' . $user->id . '/password/reset';
        $request  = Request::create($path, 'PATCH');

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', $path);
        $resources = UserManagerResource::make($user);

        $response->assertExactJson($resources->response($request)->getData(true));
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function test_store_creates_the_user_and_returns_success()
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('POST', '/api/v1/users', [
                'name'                  => self::USERNAME,
                'email'                 => self::EMAIL,
                'password'              => self::PASSWORD,
                'password_confirmation' => self::PASSWORD,
                'is_admin'              => false
            ])
            ->assertCreated();
        
        $this->assertDatabaseHas('users', [
            'name'  => self::USERNAME,
            'email' => self::EMAIL,
        ]);
    }

    /**
     * @test
     */
    public function test_store_returns_UserManagerResource_of_created_user(): void
    {
        $path = '/api/v1/users';
        $userDefinition = (new UserFactory)->definition();
        $userDefinition['password_confirmation'] = $userDefinition['password'];
        $request  = Request::create($path, 'POST');

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('POST', $path, $userDefinition)
            ->assertCreated();
            
        $user = User::where('email', $userDefinition['email'])->first();
        $resource = UserManagerResource::make($user);

        $response->assertExactJson($resource->response($request)->getData(true));
    }

    /**
     * @test
     */
    public function test_store_returns_UserManagerResource_of_created_admin(): void
    {
        $path = '/api/v1/users';
        $userDefinition = (new UserFactory)->definition();
        $userDefinition['is_admin'] = true;
        $userDefinition['password_confirmation'] = $userDefinition['password'];
        $request  = Request::create($path, 'POST');

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('POST', $path, $userDefinition)
            ->assertCreated();
            
        $user = User::where('email', $userDefinition['email'])->first();
        $resource = UserManagerResource::make($user);

        $response->assertExactJson($resource->response($request)->getData(true));
    }

    /**
     * @test
     */
    public function test_revokePATs_flushes_pats()
    {
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

    /**
     * @test
     */
    public function test_revokePATs_returns_no_content()
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->user->id . '/pats')
            ->assertNoContent();
    }

    /**
     * @test
     */
    public function test_revokePATs_always_returns_no_content()
    {
        // a fresh user has no token
        $user = User::factory()->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $user->id . '/pats')
            ->assertNoContent();
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function test_revokeWebauthnCredentials_always_returns_no_content()
    {
        DB::table('webauthn_credentials')->delete();

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->user->id . '/credentials')
            ->assertNoContent();
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function test_destroy_returns_no_content()
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $user->id)
            ->assertNoContent();
    }

    /**
     * @test
     */
    public function test_destroy_the_only_admin_returns_forbidden()
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('DELETE', '/api/v1/users/' . $this->admin->id)
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function test_promote_changes_admin_status(): void
    {
        $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', '/api/v1/users/' . $this->user->id . '/promote', [
                'is_admin' => true
            ])
            ->assertOk();

        $this->user->refresh();
        
        $this->assertTrue($this->user->isAdministrator());
    }

    /**
     * @test
     */
    public function test_promote_returns_UserManagerResource(): void
    {
        $path = '/api/v1/users/' . $this->user->id . '/promote';
        $request  = Request::create($path, 'PUT');

        $response = $this->actingAs($this->admin, 'api-guard')
            ->json('PATCH', $path, [
                'is_admin' => true
            ]);

        $this->user->refresh();
        $resources = UserManagerResource::make($this->user);

        $response->assertExactJson($resources->response($request)->getData(true));
    }


}
