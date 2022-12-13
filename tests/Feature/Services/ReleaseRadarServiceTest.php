<?php

namespace Tests\Feature\Services;

use App\Facades\Settings;
use App\Services\ReleaseRadarService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use Tests\Data\HttpRequestTestData;
use Tests\FeatureTestCase;

/**
 * @covers \App\Services\ReleaseRadarService
 */
class ReleaseRadarServiceTest extends FeatureTestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function test_manualScan_returns_no_new_release()
    {
        $url = config('2fauth.latestReleaseUrl');

        Http::preventStrayRequests();
        Http::fake([
            $url => Http::response(HttpRequestTestData::LATEST_RELEASE_BODY_NO_NEW_RELEASE, 200),
        ]);

        $releaseRadarService = new ReleaseRadarService();
        $release             = $releaseRadarService->manualScan();

        $this->assertFalse($release);
        $this->assertDatabaseHas('options', [
            'key' => 'lastRadarScan',
        ]);
        $this->assertDatabaseMissing('options', [
            'key'   => 'latestRelease',
            'value' => HttpRequestTestData::TAG_NAME,
        ]);
    }

    /**
     * @test
     */
    public function test_manualScan_returns_new_release()
    {
        $url = config('2fauth.latestReleaseUrl');

        Http::preventStrayRequests();
        Http::fake([
            $url => Http::response(HttpRequestTestData::LATEST_RELEASE_BODY_NEW_RELEASE, 200),
        ]);

        $releaseRadarService = new ReleaseRadarService();
        $release             = $releaseRadarService->manualScan();

        $this->assertEquals(HttpRequestTestData::NEW_TAG_NAME, $release);
        $this->assertDatabaseHas('options', [
            'key'   => 'latestRelease',
            'value' => HttpRequestTestData::NEW_TAG_NAME,
        ]);
        $this->assertDatabaseHas('options', [
            'key' => 'lastRadarScan',
        ]);
    }

    /**
     * @test
     */
    public function test_manualScan_succeed_when_something_fails()
    {
        $url = config('2fauth.latestReleaseUrl');

        // We do not fake the http request so an exception will be thrown
        Http::preventStrayRequests();

        $releaseRadarService = new ReleaseRadarService();
        $release             = $releaseRadarService->manualScan();

        $this->assertFalse($release);
    }

    /**
     * @test
     */
    public function test_manualScan_succeed_when_github_is_unreachable()
    {
        $url = config('2fauth.latestReleaseUrl');

        Http::preventStrayRequests();
        Http::fake([
            $url => Http::response(null, 400),
        ]);

        $releaseRadarService = new ReleaseRadarService();
        $release             = $releaseRadarService->manualScan();

        $this->assertFalse($release);
    }

    /**
     * @test
     */
    public function test_scheduleScan_runs_after_one_week()
    {
        $url = config('2fauth.latestReleaseUrl');

        Http::preventStrayRequests();
        Http::fake([
            $url => Http::response(HttpRequestTestData::LATEST_RELEASE_BODY_NEW_RELEASE, 200),
        ]);

        Settings::set('lastRadarScan', time() - (60 * 60 * 24 * 7) - 1);

        $releaseRadarService = $this->mock(ReleaseRadarService::class)->makePartial();
        $releaseRadarService->shouldAllowMockingProtectedMethods()
            ->shouldReceive('newRelease')
            ->once();

        $releaseRadarService->scheduledScan();
    }

    /**
     * @test
     */
    public function test_scheduleScan_does_not_run_before_one_week()
    {
        Settings::set('lastRadarScan', time() - (60 * 60 * 24 * 7) + 2);

        $releaseRadarService = $this->mock(ReleaseRadarService::class)->makePartial();
        $releaseRadarService->shouldAllowMockingProtectedMethods()
            ->shouldNotReceive('newRelease');

        $releaseRadarService->scheduledScan();
    }
}
