<?php

namespace Tests\Api\v1\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Services\ReleaseRadarService;
use Tests\FeatureTestCase;

/**
 * @covers \App\Http\Controllers\SystemController
 */
class SystemControllerTest extends FeatureTestCase
{
    use WithoutMiddleware;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @test
     */
    public function setUp(): void
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
                'Date',
                'userAgent',
                'Version',
                'Environment',
                'Debug',
                'Cache driver',
                'Log channel',
                'Log level',
                'DB driver',
                'PHP version',
                'Operating system',
                'interface',
            ]);
    }

    /**
     * @test
     */
    public function test_infos_returns_full_collection_when_signed_in()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/infos')
            ->assertOk()
            ->assertJsonStructure([
                'Auth guard',
                'webauthn user verification',
                'Trusted proxies',
                'options' => [
                    'showTokenAsDot',
                    'closeOtpOnCopy',
                    'copyOtpOnDisplay',
                    'useBasicQrcodeReader',
                    'displayMode',
                    'showAccountsIcons',
                    'kickUserAfter',
                    'activeGroup',
                    'rememberActiveGroup',
                    'defaultGroup',
                    'useEncryption',
                    'defaultCaptureMode',
                    'useDirectCapture',
                    'useWebauthnAsDefault',
                    'useWebauthnOnly',
                    'getOfficialIcons',
                    'checkForUpdate',
                    'lastRadarScan',
                    'latestRelease',
                    'lang',
                ],
            ]);
    }

    /**
     * @test
     */
    public function test_infos_returns_full_collection_when_signed_in_behind_proxy()
    {
        $response = $this->actingAs($this->user, 'reverse-proxy-guard')
            ->json('GET', '/infos')
            ->assertOk()
            ->assertJsonStructure([
                'Auth proxy header for user',
                'Auth proxy header for email',
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
