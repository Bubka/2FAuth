<?php

namespace Tests\Feature\Http\Auth;

use App\Http\Controllers\Auth\PersonalAccessTokenController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * PersonalAccessTokenControllerTest test class
 */
#[CoversClass(PersonalAccessTokenController::class)]
class PersonalAccessTokenControllerTest extends FeatureTestCase
{
    use MockeryPHPUnitIntegration;
    
    protected User $user;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_tokens_can_be_retrieved_for_users()
    {
        $request = Request::create('/', 'GET');

        $token1 = new Token();
        $token2 = new Token;
        $token1->client = new Client(['grant_types' => ['personal_access']]);
        $token2->client = new Client(['grant_types' => []]);
        $userTokens = (new Token)->newCollection([
            $token1, $token2,
        ]);

        $tokenRepository = m::mock(TokenRepository::class);
        $tokenRepository->shouldReceive('forUser')->andReturn($userTokens);

        $request->setUserResolver(function () {
            $user = m::mock(Authenticatable::class);
            $user->shouldReceive('getAuthIdentifier')->andReturn(1);

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
}
