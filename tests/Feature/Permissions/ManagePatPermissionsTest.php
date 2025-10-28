<?php

namespace Tests\Feature\Permissions;

use App\Facades\Settings;
use App\Http\Controllers\Auth\PersonalAccessTokenController;
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
#[CoversClass(PersonalAccessTokenController::class)]
#[CoversMethod(AppServiceProvider::class, 'boot')]
class ManagePatPermissionsTest extends FeatureTestCase
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
    #[DataProvider('providePatManagementEndPoints')]
    public function test_pat_management_endpoint_is_permitted_to_regular_user_without_useSsoOnly(string $method, string $url)
    {
        Settings::set('useSsoOnly', false);
        Settings::set('allowPatWhileSsoOnly', false);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json($method, $url);

        $this->assertNotEquals($response->getStatusCode(), Response::HTTP_FORBIDDEN);
    }

    #[Test]
    #[DataProvider('providePatManagementEndPoints')]
    public function test_pat_management_endpoint_is_forbidden_to_regular_user_with_useSsoOnly(string $method, string $url)
    {
        Settings::set('useSsoOnly', true);
        Settings::set('allowPatWhileSsoOnly', false);

        $this->actingAs($this->user, 'web-guard')
            ->json($method, $url)
            ->assertForbidden();
    }

    #[Test]
    #[DataProvider('providePatManagementEndPoints')]
    public function test_pat_management_endpoint_is_permitted_to_regular_user_with_useSsoOnly_bypassed(string $method, string $url)
    {
        Settings::set('useSsoOnly', true);
        Settings::set('allowPatWhileSsoOnly', true);

        $response = $this->actingAs($this->user, 'web-guard')
            ->json($method, $url);

        $this->assertNotEquals($response->getStatusCode(), Response::HTTP_FORBIDDEN);
    }

    #[Test]
    #[DataProvider('providePatManagementEndPoints')]
    public function test_pat_management_endpoint_is_permitted_to_admin_without_useSsoOnly(string $method, string $url)
    {
        Settings::set('useSsoOnly', false);
        Settings::set('allowPatWhileSsoOnly', false);

        $response = $this->actingAs($this->admin, 'web-guard')
            ->json($method, $url);

        $this->assertNotEquals($response->getStatusCode(), Response::HTTP_FORBIDDEN);
    }

    #[Test]
    #[DataProvider('providePatManagementEndPoints')]
    public function test_pat_management_endpoint_is_permitted_to_admin_with_useSsoOnly(string $method, string $url)
    {
        Settings::set('useSsoOnly', true);
        Settings::set('allowPatWhileSsoOnly', false);

        $response = $this->actingAs($this->admin, 'web-guard')
            ->json($method, $url);

        $this->assertNotEquals($response->getStatusCode(), Response::HTTP_FORBIDDEN);
    }

    #[Test]
    #[DataProvider('providePatManagementEndPoints')]
    public function test_pat_management_endpoint_is_permitted_to_admin_with_useSsoOnly_bypassed(string $method, string $url)
    {
        Settings::set('useSsoOnly', true);
        Settings::set('allowPatWhileSsoOnly', true);

        $response = $this->actingAs($this->admin, 'web-guard')
            ->json($method, $url);

        $this->assertNotEquals($response->getStatusCode(), Response::HTTP_FORBIDDEN);
    }

    /**
     * Provide Valid data for validation test
     */
    public static function providePatManagementEndPoints() : array
    {
        return [
            'OAUTH_PAT_ALL' => [
                'method' => 'GET',
                'url'    => '/oauth/personal-access-tokens',
            ],
            'OAUTH_PAT_STORE' => [
                'method' => 'POST',
                'url'    => '/oauth/personal-access-tokens',
            ],
            'OAUTH_PAT_DELETE' => [
                'method' => 'DELETE',
                'url'    => '/oauth/personal-access-tokens/FAKE_TOKEN_ID',
            ],
        ];
    }
}
