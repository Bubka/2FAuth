<?php

namespace Tests\Unit\Providers\Socialite;

use App\Providers\Socialite\OpenId;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\User;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use stdClass;
use Tests\TestCase;

/**
 * OpenIdProviderTest test class
 */
#[CoversClass(OpenId::class)]
class OpenIdProviderTest extends TestCase
{
    #[Test]
    public function test_it_can_map_a_user_from_an_access_token()
    {
        $request = Request::create('/');

        $provider = new OpenIdProviderStub($request, 'client_id', 'client_secret', 'redirect_uri');
        $provider->stateless();

        $provider->http = Mockery::mock(stdClass::class);
        $provider->http->expects('get')->with(NULL, [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer access_token',
            ],
        ])->andReturns($response = Mockery::mock(stdClass::class));

        $response->expects('getBody')->andReturns('{ "sub": "248289761001", "name": "Jane Doe", "given_name": "Jane", "family_name": "Doe", "preferred_username": "j.doe", "nickname": "jado", "email": "janedoe@example.com", "email_verified": "true", "groups": "myGroup" }');
        $user = $provider->user();

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('248289761001', $user->id);
        $this->assertSame('Jane Doe', $user->name);
        $this->assertSame('Jane', $user->given_name);
        $this->assertSame('Doe', $user->family_name);
        $this->assertSame('j.doe', $user->preferred_username);
        $this->assertSame('jado', $user->nickname);
        $this->assertSame('janedoe@example.com', $user->email);
        $this->assertSame('true', $user->email_verified);
        $this->assertSame('myGroup', $user->groups);
    }
    
    #[Test]
    public function test_it_can_map_a_user_from_an_access_token_with_missing_fields()
    {
        $request = Request::create('/');

        $provider = new OpenIdProviderStub($request, 'client_id', 'client_secret', 'redirect_uri');
        $provider->stateless();

        $provider->http = Mockery::mock(stdClass::class);
        $provider->http->expects('get')->with(NULL, [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer access_token',
            ],
        ])->andReturns($response = Mockery::mock(stdClass::class));

        $response->expects('getBody')->andReturns('{ "sub": "248289761001" }');
        $user = $provider->user();

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('248289761001', $user->id);
        $this->assertSame(null, $user->name);
        $this->assertSame(null, $user->given_name);
        $this->assertSame(null, $user->family_name);
        $this->assertSame(null, $user->preferred_username);
        $this->assertSame(null, $user->nickname);
        $this->assertSame(null, $user->email);
        $this->assertSame(null, $user->email_verified);
        $this->assertSame(null, $user->groups);
    }
}
