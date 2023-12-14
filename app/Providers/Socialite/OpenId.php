<?php

namespace App\Providers\Socialite;

use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;
use SocialiteProviders\Manager\SocialiteWasCalled;

class OpenId extends AbstractProvider
{
    public const IDENTIFIER = 'OPENID';

    /**
     * {@inheritdoc}
     */
    protected $scopes = ['openid profile email'];

    /**
     * {@inheritdoc}
     */
    public static function additionalConfigKeys()
    {
        return ['token_url', 'authorize_url', 'userinfo_url'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getConfig('authorize_url'), $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->getConfig('token_url');
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getConfig('userinfo_url'), [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshToken($refreshToken)
    {
        return $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::FORM_PARAMS => [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refreshToken,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'email'              => $user['email'] ?? null,
            'email_verified'     => $user['email_verified'] ?? null,
            'name'               => $user['name'] ?? null,
            'given_name'         => $user['given_name'] ?? null,
            'family_name'        => $user['family_name'] ?? null,
            'preferred_username' => $user['preferred_username'] ?? null,
            'nickname'           => $user['nickname'] ?? null,
            'groups'             => $user['groups'] ?? null,
            'id'                 => $user['sub'],
        ]);
    }
}
