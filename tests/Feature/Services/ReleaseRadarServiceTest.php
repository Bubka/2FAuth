<?php

namespace Tests\Feature\Services;

use App\Facades\Settings;
// use App\Services\ReleaseRadarService;
use Facades\App\Services\ReleaseRadarService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\HttpRequestTestData;
use Tests\FeatureTestCase;

/**
 * ReleaseRadarServiceTest test class
 */
#[CoversClass(\App\Services\ReleaseRadarService::class)]
class ReleaseRadarServiceTest extends FeatureTestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_manualScan_returns_no_new_release()
    {
        $url = config('2fauth.latestReleaseUrl');

        Http::preventStrayRequests();
        Http::fake([
            $url => Http::response(HttpRequestTestData::LATEST_RELEASE_BODY_NO_NEW_RELEASE, 200),
        ]);

        $this->assertFalse(ReleaseRadarService::manualScan());
        $this->assertDatabaseHas('options', [
            'key' => 'lastRadarScan',
        ]);
        $this->assertDatabaseMissing('options', [
            'key'   => 'latestRelease',
            'value' => HttpRequestTestData::TAG_NAME,
        ]);
    }

    #[Test]
    public function test_manualScan_returns_new_release()
    {
        $url = config('2fauth.latestReleaseUrl');

        Http::preventStrayRequests();
        Http::fake([
            $url => Http::response(HttpRequestTestData::LATEST_RELEASE_BODY_NEW_RELEASE, 200),
        ]);

        $this->assertEquals(HttpRequestTestData::NEW_TAG_NAME, ReleaseRadarService::manualScan());
        $this->assertDatabaseHas('options', [
            'key'   => 'latestRelease',
            'value' => HttpRequestTestData::NEW_TAG_NAME,
        ]);
        $this->assertDatabaseHas('options', [
            'key' => 'lastRadarScan',
        ]);
    }

    #[Test]
    public function test_manualScan_complete_when_http_call_fails()
    {
        // We do not fake the http request so an exception will be thrown
        Http::preventStrayRequests();

        $this->assertNull(ReleaseRadarService::manualScan());
    }

    #[Test]
    public function test_manualScan_succeed_when_github_is_unreachable()
    {
        $url = config('2fauth.latestReleaseUrl');

        Http::preventStrayRequests();
        Http::fake([
            $url => Http::response(null, 400),
        ]);

        $this->assertNull(ReleaseRadarService::manualScan());
    }

    #[Test]
    public function test_scheduleScan_runs_after_one_week()
    {
        $url = config('2fauth.latestReleaseUrl');

        Http::preventStrayRequests();
        Http::fake([
            $url => Http::response(HttpRequestTestData::LATEST_RELEASE_BODY_NEW_RELEASE, 200),
        ]);

        $time = time() - (60 * 60 * 24 * 7) - 1;

        Settings::set('lastRadarScan', $time);
        Settings::delete('latestRelease');

        ReleaseRadarService::scheduledScan();

        $this->assertDatabaseHas('options', [
            'key'   => 'latestRelease',
            'value' => HttpRequestTestData::NEW_TAG_NAME,
        ]);

        $this->assertDatabaseMissing('options', [
            'key'   => 'lastRadarScan',
            'value' => $time,
        ]);
    }

    #[Test]
    public function test_scheduleScan_does_not_run_before_one_week()
    {
        $url = config('2fauth.latestReleaseUrl');

        Http::preventStrayRequests();
        Http::fake([
            $url => Http::response(HttpRequestTestData::LATEST_RELEASE_BODY_NEW_RELEASE, 200),
        ]);

        $time = time() - (60 * 60 * 24 * 7) + 1;

        Settings::set('latestRelease', 'v1');
        Settings::set('lastRadarScan', $time);

        ReleaseRadarService::scheduledScan();

        $this->assertDatabaseHas('options', [
            'key'   => 'latestRelease',
            'value' => 'v1',
        ]);

        $this->assertDatabaseHas('options', [
            'key'   => 'lastRadarScan',
            'value' => $time,
        ]);
    }

    #[Test]
    public function test_scheduleScan_complete_when_http_call_fails()
    {
        // We do not fake the http request so an exception will be thrown
        Http::preventStrayRequests();

        $this->assertNull(ReleaseRadarService::scheduledScan());
    }
}
