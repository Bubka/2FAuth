<?php

namespace Tests\Feature\Services;

use App\Services\LogoLib\TfaLogoLib;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\HttpRequestTestData;
use Tests\FeatureTestCase;

/**
 * TfalogoLibTest test class
 */
#[CoversClass(TfalogoLib::class)]
class TfaLogoLibTest extends FeatureTestCase
{
    use WithoutMiddleware;

    protected TfaLogoLib $tfaLogoLib;

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
            TfalogoLib::IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            TfalogoLib::TFA_URL           => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
        ]);

        $this->tfaLogoLib = $this->app->make(TfalogoLib::class);
        $icon              = $this->tfaLogoLib->getIcon('service');

        $this->assertNotNull($icon);
        Storage::disk('icons')->assertExists($icon);
    }

    #[Test]
    public function test_getIcon_returns_null_when_github_request_fails()
    {
        Http::preventStrayRequests();
        Http::fake([
            TfalogoLib::IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            TfalogoLib::TFA_URL           => Http::response('not found', 404),
        ]);

        $this->tfaLogoLib = $this->app->make(TfalogoLib::class);
        $icon              = $this->tfaLogoLib->getIcon('service');

        $this->assertEquals(null, $icon);
    }

    #[Test]
    public function test_getIcon_returns_null_when_logo_fetching_fails()
    {
        Http::preventStrayRequests();
        Http::fake([
            TfalogoLib::IMG_URL . '*' => Http::response('not found', 404),
            TfalogoLib::TFA_URL           => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
        ]);

        $this->tfaLogoLib = $this->app->make(TfalogoLib::class);
        $icon              = $this->tfaLogoLib->getIcon('service');

        $this->assertEquals(null, $icon);
    }

    #[Test]
    public function test_getIcon_returns_null_when_no_logo_exists()
    {
        Http::preventStrayRequests();
        Http::fake([
            TfalogoLib::IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            TfalogoLib::TFA_URL           => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
        ]);

        $this->tfaLogoLib = $this->app->make(TfalogoLib::class);
        $icon              = $this->tfaLogoLib->getIcon('no_logo_should_exists_with_this_name');

        $this->assertEquals(null, $icon);
    }

    #[Test]
    public function test_TfalogoLib_loads_empty_collection_when_tfajson_fetching_fails()
    {
        Http::preventStrayRequests();
        Http::fake([
            TfalogoLib::IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            TfalogoLib::TFA_URL           => Http::response('not found', 404),
        ]);

        $this->tfaLogoLib = $this->app->make(TfalogoLib::class);
        $icon              = $this->tfaLogoLib->getIcon('service');

        $this->assertNull($icon);
        Storage::disk('logos')->assertMissing(TfalogoLib::TFA_JSON);
    }
}
