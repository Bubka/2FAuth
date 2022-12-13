<?php

namespace Tests\Feature\Services;

use App\Services\LogoService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\Data\HttpRequestTestData;
use Tests\TestCase;

/**
 * @covers \App\Services\LogoService
 */
class LogoServiceTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function test_getIcon_returns_stored_icon_file_when_logo_exists()
    {
        $svgLogo     = HttpRequestTestData::SVG_LOGO_BODY;
        $tfaJsonBody = HttpRequestTestData::TFA_JSON_BODY;

        Http::preventStrayRequests();
        Http::fake([
            'https://raw.githubusercontent.com/2factorauth/twofactorauth/master/img/*' => Http::response($svgLogo, 200),
            'https://2fa.directory/api/v3/tfa.json'                                    => Http::response($tfaJsonBody, 200),
        ]);

        Storage::fake('icons');
        Storage::fake('logos');

        $logoService = new LogoService();
        $icon        = $logoService->getIcon('twitter');

        $this->assertNotNull($icon);
        Storage::disk('icons')->assertExists($icon);
    }

    /**
     * @test
     */
    public function test_getIcon_returns_null_when_github_request_fails()
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://raw.githubusercontent.com/2factorauth/twofactorauth/master/img/*' => Http::response('not found', 404),
        ]);

        Storage::fake('icons');
        Storage::fake('logos');
        $logoService = new LogoService();

        $icon = $logoService->getIcon('twitter');

        $this->assertEquals(null, $icon);
    }

    /**
     * @test
     */
    public function test_getIcon_returns_null_when_logo_fetching_fails()
    {
        $tfaJsonBody = HttpRequestTestData::TFA_JSON_BODY;

        Http::preventStrayRequests();
        Http::fake([
            'https://2fa.directory/api/v3/tfa.json' => Http::response($tfaJsonBody, 200),
        ]);

        Storage::fake('icons');
        Storage::fake('logos');
        $logoService = new LogoService();

        $icon = $logoService->getIcon('twitter');

        $this->assertEquals(null, $icon);
    }

    /**
     * @test
     */
    public function test_getIcon_returns_null_when_no_logo_exists()
    {
        $logoService = new LogoService();

        $icon = $logoService->getIcon('no_logo_should_exists_with_this_name');

        $this->assertEquals(null, $icon);
    }

    /**
     * @test
     */
    public function test_logoService_loads_empty_collection_when_tfajson_fetching_fails()
    {
        $svgLogo = HttpRequestTestData::SVG_LOGO_BODY;

        Http::preventStrayRequests();
        Http::fake([
            'https://raw.githubusercontent.com/2factorauth/twofactorauth/master/img/*' => Http::response($svgLogo, 200),
        ]);

        Storage::fake('icons');
        Storage::fake('logos');

        $logoService = new LogoService();
        $icon        = $logoService->getIcon('twitter');

        $this->assertNull($icon);
        Storage::disk('logos')->assertMissing(LogoService::TFA_JSON);
    }
}
