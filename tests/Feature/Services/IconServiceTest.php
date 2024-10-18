<?php

namespace Tests\Feature\Services;

use App\Services\IconService;
use App\Services\LogoService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\HttpRequestTestData;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * IconServiceTest test class
 */
#[CoversClass(IconService::class)]
class IconServiceTest extends FeatureTestCase
{
    use WithoutMiddleware;

    /**
     * 
     */
    protected IconService $iconService;

    public function setUp() : void
    {
        parent::setUp();
        
        Storage::fake('icons');
        Storage::fake('logos');
        Storage::fake('imagesLink');
        
        Http::preventStrayRequests();
        Http::fake([
            LogoService::TFA_IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            LogoService::TFA_URL           => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
        ]);
        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => Http::response((new FileFactory)->image('file.png', 10, 10)->tempFile, 200),
        ]);
    }

    #[Test]
    public function test_buildFromOfficialLogo_calls_logoservice_to_get_the_icon()
    {
        $logoServiceSpy = $this->spy(LogoService::class);

        $this->iconService = $this->app->make(IconService::class);
        $this->iconService->buildFromOfficialLogo('fakeService');

        $logoServiceSpy->shouldHaveReceived('getIcon')->once()->with('fakeService');
    }

    #[Test]
    public function test_buildFromResource_stores_icon_and_returns_name()
    {
        $resource = base64_decode(OtpTestData::ICON_PNG_DATA);
        $extension = 'png';

        $this->iconService = $this->app->make(IconService::class);
        $iconName = $this->iconService->buildFromResource($resource, $extension);

        Storage::disk('icons')->assertExists($iconName);
        $this->assertEquals($resource, Storage::disk('icons')->get($iconName));
    }

    #[Test]
    public function test_buildFromResource_returns_null_when_store_fails()
    {
        Storage::shouldReceive('disk->put')
            ->andReturn(false);

        $this->iconService = $this->app->make(IconService::class);
        $iconName = $this->iconService->buildFromResource('lorem', 'ipsum');

        $this->assertNull($iconName);
    }

    #[Test]
    #[DataProvider('badBuildFromResourceInputsProvider')]
    public function test_buildFromResource_with_bad_inputs_returns_null($resource, $extension)
    {
        $this->iconService = $this->app->make(IconService::class);
        $iconName = $this->iconService->buildFromResource($resource, $extension);

        $this->assertNull($iconName);
    }

    /**
     * Provide bad inputs for buildFromResource test
     */
    public static function badBuildFromResourceInputsProvider()
    {
        return [
            'NULL_RESOURCE' => [
                null,
                'png',
            ],
            'EMPTY_RESOURCE' => [
                '',
                'png',
            ],
            'BAD_RESOURCE_TYPE' => [
                false,
                'png',
            ],
            'NULL_EXTENSION' => [
                base64_decode(OtpTestData::ICON_PNG_DATA),
                null,
            ],
            'EMPTY_EXTENSION' => [
                base64_decode(OtpTestData::ICON_PNG_DATA),
                '',
            ],
            'BAD_EXTENSION_TYPE' => [
                base64_decode(OtpTestData::ICON_PNG_DATA),
                false,
            ],
            'UNSUPPORTED_EXTENSION' => [
                base64_decode(OtpTestData::ICON_GIF_DATA),
                'gif',
            ],
            'UNCONSISTENT_EXTENSION' => [
                base64_decode(OtpTestData::ICON_PNG_DATA),
                'jpeg',
            ],
        ];
    }

    #[Test]
    #[DataProviderExternal(IconStoreServiceTest::class, 'supportedMimeTypesProvider')]
    public function test_buildFromRemoteImage_stores_icon_and_returns_name($name, $base64content, $mimetype)
    {
        $imageUrl = 'https://' . $name;
        $resource = base64_decode($base64content);

        Http::preventStrayRequests();
        Http::fake([
            $imageUrl => Http::response($resource, 200),
        ]);

        $this->iconService = $this->app->make(IconService::class);
        $iconName = $this->iconService->buildFromRemoteImage($imageUrl);

        Storage::disk('icons')->assertExists($iconName);
        Storage::disk('imagesLink')->assertMissing($iconName);
        $this->assertEquals($resource, Storage::disk('icons')->get($iconName));
    }

    #[Test]
    #[DataProvider('buildFromRemoteImageInvalidUrlProvider')]
    public function test_buildFromRemoteImage_returns_null_when_url_is_invalid()
    {
        $imageUrl = 'not_a_valid_url';

        $validator = Mockery::mock('stdClass');
        Validator::swap($validator);
        Validator::shouldReceive('make')
            ->once()
            ->with([$imageUrl], ['url'])
            ->andReturn($validator);

        Validator::shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $this->iconService = $this->app->make(IconService::class);
        $iconName = $this->iconService->buildFromRemoteImage($imageUrl);

        $this->assertNull($iconName);
    }

    /**
     * Provide invalid urls for buildFromRemoteImage test
     */
    public static function buildFromRemoteImageInvalidUrlProvider()
    {
        return [
            'FTP' => [
                'ftp://example.com/file.txt',
            ],
            'NO_SCHEME' => [
                'example.com/file.txt',
            ],
            'BRACKET' => [
                'http://example.com/file[/].html',
            ],
        ];
    }

    #[Test]
    public function test_buildFromRemoteImage_returns_null_when_remote_img_is_unreachable()
    {
        $imageUrl = 'https://icon.png';

        $this->iconService = $this->app->make(IconService::class);
        $iconName = $this->iconService->buildFromRemoteImage($imageUrl);

        $this->assertNull($iconName);
    }

    #[Test]
    public function test_buildFromRemoteImage_returns_null_when_remote_img_is_not_supported()
    {
        $imageUrl = 'https://icon.gif';

        Http::preventStrayRequests();
        Http::fake([
            $imageUrl => Http::response('fakeBody', 200),
        ]);

        $this->iconService = $this->app->make(IconService::class);
        $iconName = $this->iconService->buildFromRemoteImage($imageUrl);

        $this->assertNull($iconName);
    }

    #[Test]
    public function test_buildFromRemoteImage_returns_null_when_remote_img_is_not_valid_resource()
    {
        $imageUrl = 'https://icon.png';
        $resource = 'invalid_img_resource';

        Http::preventStrayRequests();
        Http::fake([
            $imageUrl => Http::response($resource, 200),
        ]);

        $this->iconService = $this->app->make(IconService::class);
        $iconName = $this->iconService->buildFromRemoteImage($imageUrl);

        $this->assertNull($iconName);
    }
}