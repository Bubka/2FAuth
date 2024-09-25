<?php

namespace Tests\Unit\Providers\Socialite;

use App\Providers\Socialite\OpenId;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\Token;
use Laravel\Socialite\Two\User;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SocialiteProviders\Manager\Helpers\ConfigRetriever;
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

    #[Test]
    public function test_it_fetches_token_url_from_config()
    {
        $tokenUrl = 'http://token.url';
        config(['services.openid.token_url' => $tokenUrl]);
        $request = Request::create('/');

        $provider = new OpenIdProviderStub($request, 'client_id', 'client_secret', 'redirect_uri');
        $provider->http = Mockery::mock(stdClass::class);

        $config = (new ConfigRetriever)->fromServices('openid', $provider->additionalConfigKeys());
        $provider->setConfig($config);

        $provider->http->expects('post')->with($tokenUrl, [
            RequestOptions::HEADERS => ['Accept' => 'application/json'],
            RequestOptions::FORM_PARAMS => [
                'grant_type' => 'refresh_token',
                'refresh_token' => 'refresh_token',
                'client_id' => null,
                'client_secret' => null,
            ],
        ])->andReturns($response = Mockery::mock(stdClass::class));

        $response->expects('getBody')->andReturns('{ "access_token" : "access_token", "refresh_token" : "refresh_token", "expires_in" : 3600, "scope" : "scope1,scope2" }');
        $token = $provider->refreshToken('refresh_token');

        $this->assertInstanceOf(Token::class, $token);
    }

    #[Test]
    public function test_it_redirects_to_url_from_config()
    {
        $authUrl = 'http://auth.url';
        config(['services.openid.authorize_url' => $authUrl]);
        $request = Request::create('/');

        $provider = new OpenIdProviderStub($request, 'client_id', 'client_secret', 'redirect_uri');
        $provider->http = Mockery::mock(stdClass::class);
        $provider->stateless();

        $config = (new ConfigRetriever)->fromServices('openid', $provider->additionalConfigKeys());
        $provider->setConfig($config);

        $response = $provider->redirect();

        $this->assertStringStartsWith($authUrl, $response->getTargetUrl());
    }
}
