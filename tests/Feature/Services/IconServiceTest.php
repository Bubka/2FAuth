<?php

namespace Tests\Feature\Services;

use App\Facades\LogoLib;
use App\Services\IconService;
use App\Services\LogoLib\TfaLogoLib;
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
use Tests\Data\CommonDataProvider;
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

    protected IconService $iconService;

    protected function setUp() : void
    {
        parent::setUp();

        Storage::fake('icons');
        Storage::fake('iconPacks');
        Storage::fake('logos');
        Storage::fake('imagesLink');

        Http::preventStrayRequests();
        Http::fake([
            CommonDataProvider::TFA_URL            => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            CommonDataProvider::SELFH_URL          => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            CommonDataProvider::DASHBOARDICONS_URL => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            TfaLogoLib::TFA_JSON_URL               => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
        ]);
        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => Http::response((new FileFactory)->image('file.png', 10, 10)->tempFile, 200),
        ]);
    }

    #[Test]
    public function test_build_from_official_logo_calls_logo_lib_to_get_the_icon()
    {
        LogoLib::shouldReceive('driver->getIcon')
            ->once()
            ->with('fakeService')
            ->andReturn('value');

        $this->iconService = $this->app->make(IconService::class);
        $this->iconService->buildFromOfficialLogo('fakeService');
    }

    #[Test]
    public function test_build_from_resource_stores_icon_and_returns_name()
    {
        $resource  = base64_decode(OtpTestData::ICON_PNG_DATA);
        $extension = 'png';

        $this->iconService = $this->app->make(IconService::class);
        $iconName          = $this->iconService->buildFromResource($resource, $extension);

        Storage::disk('icons')->assertExists($iconName);
        $this->assertEquals($resource, Storage::disk('icons')->get($iconName));
    }

    #[Test]
    public function test_build_from_resource_returns_null_when_store_fails()
    {
        Storage::shouldReceive('disk->mimeType')
            ->andReturn('image/png');

        Storage::shouldReceive('disk->put')
            ->andReturn(false);

        $this->iconService = $this->app->make(IconService::class);
        $iconName          = $this->iconService->buildFromResource('lorem', 'ipsum');

        $this->assertNull($iconName);
    }

    #[Test]
    #[DataProvider('badBuildFromResourceInputsProvider')]
    public function test_build_from_resource_with_bad_inputs_returns_null($resource, $extension)
    {
        $this->iconService = $this->app->make(IconService::class);
        $iconName          = $this->iconService->buildFromResource($resource, $extension);

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
    public function test_build_from_remote_image_stores_icon_and_returns_name($name, $base64content, $mimetype)
    {
        $imageUrl = 'https://' . $name;
        $resource = base64_decode($base64content);

        Http::preventStrayRequests();
        Http::fake([
            $imageUrl => Http::response($resource, 200),
        ]);

        $this->iconService = $this->app->make(IconService::class);
        $iconName          = $this->iconService->buildFromRemoteImage($imageUrl);

        Storage::disk('icons')->assertExists($iconName);
        Storage::disk('imagesLink')->assertMissing($iconName);
        $this->assertEquals($resource, Storage::disk('icons')->get($iconName));
    }

    #[Test]
    #[DataProvider('buildFromRemoteImageInvalidUrlProvider')]
    public function test_build_from_remote_image_returns_null_when_url_is_invalid()
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
        $iconName          = $this->iconService->buildFromRemoteImage($imageUrl);

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
    public function test_build_from_remote_image_returns_null_when_remote_img_is_unreachable()
    {
        Http::fake([
            'example.com/*' => Http::response(null, 400),
        ]);

        $imageUrl = 'https://www.example.com/image.png';

        $this->iconService = $this->app->make(IconService::class);
        $iconName          = $this->iconService->buildFromRemoteImage($imageUrl);

        $this->assertNull($iconName);
    }

    #[Test]
    public function test_build_from_remote_image_returns_null_when_remote_img_is_not_supported()
    {
        $imageUrl = 'https://icon.gif';

        Http::preventStrayRequests();
        Http::fake([
            $imageUrl => Http::response('fakeBody', 200),
        ]);

        $this->iconService = $this->app->make(IconService::class);
        $iconName          = $this->iconService->buildFromRemoteImage($imageUrl);

        $this->assertNull($iconName);
    }

    #[Test]
    public function test_build_from_remote_image_returns_null_when_remote_img_is_not_valid_resource()
    {
        $imageUrl = 'https://icon.png';
        $resource = 'invalid_img_resource';

        Http::preventStrayRequests();
        Http::fake([
            $imageUrl => Http::response($resource, 200),
        ]);

        $this->iconService = $this->app->make(IconService::class);
        $iconName          = $this->iconService->buildFromRemoteImage($imageUrl);

        $this->assertNull($iconName);
    }

    #[Test]
    public function test_get_icon_packs_returns_icon_folders_which_contains_supported_icon_format()
    {
        Storage::disk('iconPacks')->put('packDirWithSvg/' . OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA);
        Storage::disk('iconPacks')->put('packDirWithPng/' . OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('iconPacks')->put('packDirWithJpeg/' . OtpTestData::ICON_JPEG, base64_decode(OtpTestData::ICON_JPEG_DATA));
        Storage::disk('iconPacks')->put('packDirWithWebp/' . OtpTestData::ICON_WEBP, base64_decode(OtpTestData::ICON_WEBP_DATA));
        Storage::disk('iconPacks')->put('packDirWithBmp/' . OtpTestData::ICON_BMP, base64_decode(OtpTestData::ICON_BMP_DATA));

        $this->iconService = $this->app->make(IconService::class);
        $iconPacks         = $this->iconService->getIconPacks();

        $this->assertTrue($iconPacks->contains('name', 'packDirWithSvg'));
        $this->assertTrue($iconPacks->contains('name', 'packDirWithPng'));
        $this->assertTrue($iconPacks->contains('name', 'packDirWithJpeg'));
        $this->assertTrue($iconPacks->contains('name', 'packDirWithWebp'));
        $this->assertTrue($iconPacks->contains('name', 'packDirWithBmp'));
        $this->assertEquals(5, $iconPacks->count());
    }

    #[Test]
    public function test_get_icon_packs_returns_iconpack_in_subfolder()
    {
        Storage::disk('iconPacks')->put('packDir/variantDark/' . OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA);
        Storage::disk('iconPacks')->put('packDir/variantLight/' . OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA);

        $this->iconService = $this->app->make(IconService::class);
        $iconPacks         = $this->iconService->getIconPacks();

        $this->assertTrue($iconPacks->contains('name', 'packDir/variantDark'));
        $this->assertTrue($iconPacks->contains('name', 'packDir/variantLight'));
    }

    #[Test]
    public function test_get_icon_packs_returns_empty_collection_when_no_iconpack_folder_exists()
    {
        $this->iconService = $this->app->make(IconService::class);
        $iconPacks         = $this->iconService->getIconPacks();

        $this->assertEquals(0, $iconPacks->count());
    }

    #[Test]
    public function test_get_icon_packs_returns_empty_collection_when_iconpack_starts_with_dot()
    {
        Storage::disk('iconPacks')->put('.packDirWithSvg/' . OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA);

        $this->iconService = $this->app->make(IconService::class);
        $iconPacks         = $this->iconService->getIconPacks();

        $this->assertEquals(0, $iconPacks->count());
    }

    #[Test]
    public function test_get_icon_packs_returns_empty_collection_when_no_supported_icon_format_is_found()
    {
        Storage::disk('iconPacks')->makeDirectory('emptyPackDir');
        Storage::disk('iconPacks')->put('packDirWithUnsupportedFile/test.txt', 'just some text content');

        $this->iconService = $this->app->make(IconService::class);
        $iconPacks         = $this->iconService->getIconPacks();

        $this->assertEquals(0, $iconPacks->count());
    }

    #[Test]
    public function test_get_icon_packs_returns_empty_collection_when_iconpack_contains_faked_mimetype()
    {
        Storage::disk('iconPacks')->put('packDirWithPng/' . OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_GIF_DATA));

        $this->iconService = $this->app->make(IconService::class);
        $iconPacks         = $this->iconService->getIconPacks();

        $this->assertEquals(0, $iconPacks->count());
    }

    #[Test]
    public function test_get_icon_packs_returns_empty_collection_when_iconpack_contains_faked_image()
    {
        Storage::disk('iconPacks')->put('packDirWithPng/' . OtpTestData::ICON_PNG, 'fakeFileContentThatIsNotAnImage');

        $this->iconService = $this->app->make(IconService::class);
        $iconPacks         = $this->iconService->getIconPacks();

        $this->assertEquals(0, $iconPacks->count());
    }
}
