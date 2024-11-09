<?php

namespace Tests\Feature\Services;

use App\Services\LogoService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\HttpRequestTestData;
use Tests\FeatureTestCase;

/**
 * LogoServiceTest test class
 */
#[CoversClass(LogoService::class)]
class LogoServiceTest extends FeatureTestCase
{
    use WithoutMiddleware;

    protected LogoService $logoService;

    public function setUp() : void
    {
        parent::setUp();

        Storage::fake('icons');
        Storage::fake('logos');
        Storage::fake('imagesLink');
    }

    #[Test]
    public function test_getIcon_returns_stored_icon_file_when_logo_exists()
    {
        Http::preventStrayRequests();
        Http::fake([
            LogoService::TFA_IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            LogoService::TFA_URL           => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
        ]);

        $this->logoService = $this->app->make(LogoService::class);
        $icon              = $this->logoService->getIcon('service');

        $this->assertNotNull($icon);
        Storage::disk('icons')->assertExists($icon);
    }

    #[Test]
    public function test_getIcon_returns_null_when_github_request_fails()
    {
        Http::preventStrayRequests();
        Http::fake([
            LogoService::TFA_IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            LogoService::TFA_URL           => Http::response('not found', 404),
        ]);

        $this->logoService = $this->app->make(LogoService::class);
        $icon              = $this->logoService->getIcon('service');

        $this->assertEquals(null, $icon);
    }

    #[Test]
    public function test_getIcon_returns_null_when_logo_fetching_fails()
    {
        Http::preventStrayRequests();
        Http::fake([
            LogoService::TFA_IMG_URL . '*' => Http::response('not found', 404),
            LogoService::TFA_URL           => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
        ]);

        $this->logoService = $this->app->make(LogoService::class);
        $icon              = $this->logoService->getIcon('service');

        $this->assertEquals(null, $icon);
    }

    #[Test]
    public function test_getIcon_returns_null_when_no_logo_exists()
    {
        Http::preventStrayRequests();
        Http::fake([
            LogoService::TFA_IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            LogoService::TFA_URL           => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
        ]);

        $this->logoService = $this->app->make(LogoService::class);
        $icon              = $this->logoService->getIcon('no_logo_should_exists_with_this_name');

        $this->assertEquals(null, $icon);
    }

    #[Test]
    public function test_logoService_loads_empty_collection_when_tfajson_fetching_fails()
    {
        Http::preventStrayRequests();
        Http::fake([
            LogoService::TFA_IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            LogoService::TFA_URL           => Http::response('not found', 404),
        ]);

        $this->logoService = $this->app->make(LogoService::class);
        $icon              = $this->logoService->getIcon('service');

        $this->assertNull($icon);
        Storage::disk('logos')->assertMissing(LogoService::TFA_JSON);
    }
}
