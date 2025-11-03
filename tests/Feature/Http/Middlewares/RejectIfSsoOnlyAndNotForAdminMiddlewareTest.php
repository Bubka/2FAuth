<?php

namespace Tests\Feature\Http\Middlewares;

use App\Facades\Settings;
use App\Http\Middleware\RejectIfSsoOnlyAndNotForAdmin;
use App\Models\User;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * RejectIfSsoOnlyAndNotForAdminMiddlewareTest test class
 */
#[CoversClass(RejectIfSsoOnlyAndNotForAdmin::class)]
class RejectIfSsoOnlyAndNotForAdminMiddlewareTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $admin;

    private const PASSWORD = 'password';

    protected function setUp() : void
    {
        parent::setUp();

        $this->user  = User::factory()->create();
        $this->admin = User::factory()->administrator()->create([
            'password' => self::PASSWORD,
        ]);

        Settings::set('useSsoOnly', true);
    }

    #[Test]
    public function test_admin_login_with_password_returns_success()
    {
        $this->json('POST', '/user/login', [
            'email'    => $this->admin->email,
            'password' => self::PASSWORD,
        ])
            ->assertOk();
    }

    #[Test]
    #[CoversNothing]
    public function test_admin_login_with_webauthn_returns_success()
    {
        // See WebAuthnLoginControllerTest->test_webauthn_login_of_admin_returns_success_even_with_sso_only_enabled()
    }

    #[Test]
    public function test_login_of_missing_account_returns_NOT_ALLOWED()
    {
        $this->json('POST', '/user/login', [
            'email'    => 'missing@user.com',
            'password' => self::PASSWORD,
        ])
            ->assertMethodNotAllowed();
    }

    #[Test]
    #[DataProvider('providePublicEndPoints')]
    public function test_public_endpoint_does_not_return_NOT_ALLOWED_if_requested_for_an_admin(string $method, string $url)
    {
        $expectedResponseCodes = [
            Response::HTTP_OK,
            Response::HTTP_UNPROCESSABLE_ENTITY,
        ];

        $response = $this->json($method, $url, [
            'email' => $this->admin->email,
        ]);

        $this->assertContains($response->getStatusCode(), $expectedResponseCodes);
    }

    #[Test]
    #[DataProvider('providePublicEndPoints')]
    public function test_public_endpoint_returns_NOT_ALLOWED_if_requested_for_regular_user(string $method, string $url)
    {
        $this->json($method, $url)
            ->assertMethodNotAllowed();
    }

    /**
     * Provide Valid data for validation test
     */
    public static function providePublicEndPoints() : array
    {
        return [
            'PWD_REGISTER' => [
                'method' => 'POST',
                'url'    => '/user',
            ],
            'PWD_LOGIN' => [
                'method' => 'POST',
                'url'    => '/user/login',
            ],
            'PWD_LOST' => [
                'method' => 'POST',
                'url'    => '/user/password/lost',
            ],
            'PWD_RESET' => [
                'method' => 'POST',
                'url'    => '/user/password/reset',
            ],
            'WEBAUTHN_LOGIN' => [
                'method' => 'POST',
                'url'    => '/webauthn/login',
            ],
            'WEBAUTHN_LOGIN_OPTIONS' => [
                'method' => 'POST',
                'url'    => '/webauthn/login/options',
            ],
            'WEBAUTHN_LOST' => [
                'method' => 'POST',
                'url'    => '/webauthn/lost',
            ],
            'WEBAUTHN_RECOVER' => [
                'method' => 'POST',
                'url'    => '/webauthn/recover',
            ],
        ];
    }
}
