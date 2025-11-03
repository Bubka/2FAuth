<?php

namespace Tests\Unit\Providers\Socialite;

use App\Providers\Socialite\OpenId;
use Mockery;
use SocialiteProviders\Manager\OAuth2\User;
use stdClass;

class OpenIdProviderStub extends OpenId
{
    /**
     * @var \GuzzleHttp\Client|\Mockery\MockInterface
     */
    public $http;

    // protected function getAuthUrl($state)
    // {
    //     return $this->buildAuthUrlFromBase('http://auth.url', $state);
    // }

    // protected function getTokenUrl()
    // {
    //     return 'http://token.url';
    // }

    /**
     * Get the access token response for the given code.
     *
     * @param  string  $code
     * @return array
     */
    public function getAccessTokenResponse($code)
    {
        return ['access_token' => 'access_token'];
    }

    // protected function getUserByToken($token)
    // {
    //     return ['id' => 'foo'];
    // }

    // protected function mapUserToObject(array $user)
    // {
    //     return (new User)->map(['id' => $user['id']]);
    // }

    /**
     * Get a fresh instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client|\Mockery\MockInterface
     */
    protected function getHttpClient()
    {
        if ($this->http) {
            return $this->http;
        }

        return $this->http = Mockery::mock(stdClass::class);
    }
}
