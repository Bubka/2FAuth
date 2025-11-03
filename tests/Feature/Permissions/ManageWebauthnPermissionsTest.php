<?php

namespace Tests\Feature\Permissions;

use App\Facades\Settings;
use App\Http\Controllers\Auth\WebAuthnManageController;
use App\Http\Controllers\Auth\WebAuthnRegisterController;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * ManagePatPermissionsTest test class
 */
#[CoversClass(WebAuthnManageController::class)]
#[CoversClass(WebAuthnRegisterController::class)]
#[CoversMethod(AppServiceProvider::class, 'boot')]
class ManageWebauthnPermissionsTest extends FeatureTestCase
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
    }

    #[Test]
    #[DataProvider('provideWebauthnManagementEndPoints')]
    public function test_webauthn_management_endpoint_is_permitted_to_regular_user_without_useSsoOnly(string $method, string $url)
    {
        Settings::set('useSsoOnly', false);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json($method, $url);

        $this->assertNotEquals($response->getStatusCode(), Response::HTTP_FORBIDDEN);
    }

    #[Test]
    #[DataProvider('provideWebauthnManagementEndPoints')]
    public function test_webauthn_management_endpoint_is_forbidden_to_regular_user_with_useSsoOnly(string $method, string $url)
    {
        Settings::set('useSsoOnly', true);
        
        $this->actingAs($this->user, 'web-guard')
            ->json($method, $url)
            ->assertForbidden();
    }

    #[Test]
    #[DataProvider('provideWebauthnManagementEndPoints')]
    public function test_webauthn_management_endpoint_is_permitted_to_admin_without_useSsoOnly(string $method, string $url)
    {
        Settings::set('useSsoOnly', false);

        $response = $this->actingAs($this->admin, 'web-guard')
            ->json($method, $url);

        $this->assertNotEquals($response->getStatusCode(), Response::HTTP_FORBIDDEN);
    }

    #[Test]
    #[DataProvider('provideWebauthnManagementEndPoints')]
    public function test_webauthn_management_endpoint_is_permitted_to_admin_with_useSsoOnly(string $method, string $url)
    {
        Settings::set('useSsoOnly', true);

        $response = $this->actingAs($this->admin, 'web-guard')
            ->json($method, $url);

        $this->assertNotEquals($response->getStatusCode(), Response::HTTP_FORBIDDEN);
    }

    /**
     * Provide Valid data for validation test
     */
    public static function provideWebauthnManagementEndPoints() : array
    {
        return [
            'WEBAUTHN_REGISTER_OPTIONS' => [
                'method' => 'POST',
                'url'    => '/webauthn/register/options',
            ],
            'WEBAUTHN_REGISTER' => [
                'method' => 'POST',
                'url'    => '/webauthn/register',
            ],
            'WEBAUTHN_CREDENTIALS_ALL' => [
                'method' => 'GET',
                'url'    => '/webauthn/credentials',
            ],
            'WEBAUTHN_CREDENTIALS_PATCH' => [
                'method' => 'PATCH',
                'url'    => '/webauthn/credentials/FAKE_CREDENTIAL_ID/name',
            ],
            'WEBAUTHN_CREDENTIALS_DELETE' => [
                'method' => 'DELETE',
                'url'    => '/webauthn/credentials/FAKE_CREDENTIAL_ID',
            ],
        ];
    }
}
