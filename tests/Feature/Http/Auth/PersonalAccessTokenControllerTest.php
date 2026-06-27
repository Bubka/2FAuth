<?php

namespace Tests\Feature\Http\Auth;

use App\Http\Controllers\Auth\PersonalAccessTokenController;
use App\Models\User;
use App\Services\Auth\PassportTokenRepository;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\FeatureTestCase;

/**
 * PersonalAccessTokenControllerTest test class
 */
#[CoversClass(PersonalAccessTokenController::class)]
class PersonalAccessTokenControllerTest extends FeatureTestCase
{
    use MockeryPHPUnitIntegration;
    
    protected User $user;
    protected string $userProvider = "users";

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }
    
    #[Test]
    public function test_tokens_can_be_retrieved_for_users()
    {
        Gate::expects('denies')
            ->with('manage-pat')
            ->times(2)
            ->andReturn(false);

        $request = Request::create('/', 'GET');

        $token1 = new Token();
        $token2 = new Token;
        $token1->client = new Client(['grant_types' => ['personal_access']]);
        $token2->client = new Client(['grant_types' => []]);
        $userTokens = (new Token)->newCollection([
            $token1, $token2,
        ]);

        $tokenRepository = m::mock(PassportTokenRepository::class);
        $tokenRepository->shouldReceive('forUser')->andReturn($userTokens);

        $request->setUserResolver(function () {
            $user = m::mock(Authenticatable::class);
            $user->shouldReceive('getAuthIdentifier')->andReturn(1);
            $user->shouldReceive('getProviderName')->andReturn($this->userProvider);

            return $user;
        });

        $validator = m::mock(Factory::class);
        $controller = new PersonalAccessTokenController($tokenRepository, $validator);

        $this->assertCount(1, $controller->forUser($request));
        $this->assertEquals($token1, $controller->forUser($request)[0]);
    }

    #[Test]
    public function test_forUser_ensures_personal_access_client_exists()
    {
        DB::table('oauth_clients')->where('personal_access_client', true)->delete();

        $this->actingAs($this->user, 'web-guard')
            ->json('GET', '/oauth/personal-access-tokens')
            ->assertOk();

        $this->assertDatabaseHas('oauth_clients', [
            'personal_access_client' => true,
            'provider' => $this->user->getProviderName(),
        ]);
    }
    
    #[Test]
    public function test_tokens_can_be_updated()
    {
        Gate::expects('denies')
            ->with('manage-pat')
            ->zeroOrMoreTimes()
            ->andReturn(false);

        Passport::tokensCan([
            'user' => 'first',
            'user-admin' => 'second',
        ]);

        $result = m::mock(PersonalAccessTokenResult::class);

        $request = Request::create('/', 'GET', ['name' => 'token name', 'scopes' => ['user', 'user-admin']]);

        $request->setUserResolver(function () use ($result) {
            $user = m::mock(Authenticatable::class);
            $user->shouldReceive('getProviderName')->andReturn($this->userProvider);
            $user->shouldReceive('createToken')
                ->with('token name', ['user', 'user-admin'])
                ->andReturn($result);

            return $user;
        });

        $validator = m::mock(Factory::class);
        $validator->shouldReceive('make')->once()->with([
            'name' => 'token name',
            'scopes' => ['user', 'user-admin'],
        ], [
            'name' => ['required', 'max:255'],
            'scopes' => ['array', Rule::in(Passport::scopeIds())],
        ])->andReturn($validator);
        $validator->shouldReceive('validate')->once();

        $tokenRepository = m::mock(PassportTokenRepository::class);
        $controller = new PersonalAccessTokenController($tokenRepository, $validator);

        $this->assertSame($result, $controller->store($request));
    }

    #[Test]
    public function test_store_ensures_personal_access_client_exists()
    {
        DB::table('oauth_clients')->where('personal_access_client', true)->delete();

        $this->actingAs($this->user, 'web-guard')
            ->json('POST', '/oauth/personal-access-tokens', [
                'name' => 'token name',
            ])
            ->assertOk();

        $this->assertDatabaseHas('oauth_clients', [
            'personal_access_client' => true,
            'provider' => $this->user->getProviderName(),
        ]);
    }
    
    #[Test]
    public function test_tokens_can_be_deleted()
    {
        Gate::expects('denies')
            ->with('manage-pat')
            ->zeroOrMoreTimes()
            ->andReturn(false);

        $request = Request::create('/', 'GET');

        $token1 = m::mock(Token::class.'[revoke]');
        $token1->id = 1;
        $token1->shouldReceive('revoke')->once();

        $tokenRepository = m::mock(PassportTokenRepository::class);
        $tokenRepository->shouldReceive('findForUser')->andReturn($token1);

        $request->setUserResolver(function () {
            $user = m::mock(Authenticatable::class);
            $user->shouldReceive('getAuthIdentifier')->andReturn(1);
            $user->shouldReceive('getProviderName')->andReturn($this->userProvider);

            return $user;
        });

        $validator = m::mock(Factory::class);
        $controller = new PersonalAccessTokenController($tokenRepository, $validator);

        $response = $controller->destroy($request, 1);

        $this->assertSame(Response::HTTP_NO_CONTENT, $response->status());
    }
    
    #[Test]
    public function test_not_found_response_is_returned_if_user_doesnt_have_token()
    {
        Gate::expects('denies')
            ->with('manage-pat')
            ->zeroOrMoreTimes()
            ->andReturn(false);

        $user = m::mock(Authenticatable::class);
        $user->shouldReceive('getAuthIdentifier')->andReturn(1);

        $tokenRepository = m::mock(PassportTokenRepository::class);
        $tokenRepository->shouldReceive('findForUser')->with(3, $user)->andReturnNull();

        $request = Request::create('/', 'GET');
        $request->setUserResolver(fn () => $user);

        $validator = m::mock(Factory::class);
        $controller = new PersonalAccessTokenController($tokenRepository, $validator);

        $this->assertSame(404, $controller->destroy($request, 3)->status());
    }
}
