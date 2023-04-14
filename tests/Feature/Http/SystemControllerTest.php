<?php

namespace Tests\Feature\Http;

use App\Models\User;
use App\Services\ReleaseRadarService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\FeatureTestCase;

/**
 * @covers \App\Http\Controllers\SystemController
 */
class SystemControllerTest extends FeatureTestCase
{
    use WithoutMiddleware;

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function test_infos_returns_only_base_collection()
    {
        $response = $this->json('GET', '/infos')
            ->assertOk()
            ->assertJsonStructure([
                'common' => [
                    'Date',
                    'userAgent',
                    'Version',
                    'Environment',
                    'Install path',
                    'Debug',
                    'Cache driver',
                    'Log channel',
                    'Log level',
                    'DB driver',
                    'PHP version',
                    'Operating system',
                    'interface',
                ],
            ])
            ->assertJsonMissing([
                'user_preferences',
                'admin_settings',
            ]);
    }

    /**
     * @test
     */
    public function test_infos_returns_user_preferences_when_signed_in()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/infos')
            ->assertOk()
            ->assertJsonStructure([
                'user_preferences' => [
                    'showOtpAsDot',
                    'closeOtpOnCopy',
                    'copyOtpOnDisplay',
                    'useBasicQrcodeReader',
                    'displayMode',
                    'showAccountsIcons',
                    'kickUserAfter',
                    'activeGroup',
                    'rememberActiveGroup',
                    'defaultGroup',
                    'defaultCaptureMode',
                    'useDirectCapture',
                    'useWebauthnOnly',
                    'getOfficialIcons',
                    'lang',
                ],
            ]);
    }

    /**
     * @test
     */
    public function test_infos_returns_admin_settings_when_signed_in_as_admin()
    {
        /**
         * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
         */
        $admin = User::factory()->administrator()->create();

        $response = $this->actingAs($admin, 'api-guard')
            ->json('GET', '/infos')
            ->assertOk()
            ->assertJsonStructure([
                'admin_settings' => [
                    'useEncryption',
                    'lastRadarScan',
                    'checkForUpdate',
                ],
            ]);
    }

    /**
     * @test
     */
    public function test_infos_returns_proxy_collection_when_signed_in_behind_proxy()
    {
        $response = $this->actingAs($this->user, 'reverse-proxy-guard')
            ->json('GET', '/infos')
            ->assertOk()
            ->assertJsonStructure([
                'common' => [
                    'Auth proxy header for user',
                    'Auth proxy header for email',
                ],
            ]);
    }

    /**
     * @test
     */
    public function test_latestrelease_runs_manual_scan()
    {
        $releaseRadarService = $this->mock(ReleaseRadarService::class)->makePartial();
        $releaseRadarService->shouldReceive('manualScan')
            ->once()
            ->andReturn('new_release');

        $response = $this->json('GET', '/latestRelease')
            ->assertOk()
            ->assertJson([
                'newRelease' => 'new_release',
            ]);
    }
}
