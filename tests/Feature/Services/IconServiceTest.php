<?php

namespace Tests\Feature\Services;

use App\Services\IconService;
use App\Services\LogoService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\HttpRequestTestData;
use Tests\TestCase;

/**
 * IconServiceTest test class
 */
#[CoversClass(IconService::class)]
class IconServiceTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp() : void
    {
        parent::setUp();
    }

    // #[Test]
    // #[DataProvider('iconResourceProvider')]
    // public function test_set_icon_stores_and_set_the_icon($res, $ext)
    // {
    //     Storage::fake('imagesLink');
    //     Storage::fake('icons');

    //     $previousIcon = $this->customTotpTwofaccount->icon;
    //     $this->customTotpTwofaccount->setIcon($res, $ext);

    //     $this->assertNotEquals($previousIcon, $this->customTotpTwofaccount->icon);

    //     Storage::disk('icons')->assertExists($this->customTotpTwofaccount->icon);
    //     Storage::disk('imagesLink')->assertMissing($this->customTotpTwofaccount->icon);
    // }

    // /**
    //  * Provide data for Icon store tests
    //  */
    // public static function iconResourceProvider()
    // {
    //     return [
    //         'PNG' => [
    //             base64_decode(OtpTestData::ICON_PNG_DATA),
    //             'png',
    //         ],
    //         'JPG' => [
    //             base64_decode(OtpTestData::ICON_JPEG_DATA),
    //             'jpg',
    //         ],
    //         'WEBP' => [
    //             base64_decode(OtpTestData::ICON_WEBP_DATA),
    //             'webp',
    //         ],
    //         'BMP' => [
    //             base64_decode(OtpTestData::ICON_BMP_DATA),
    //             'bmp',
    //         ],
    //         'SVG' => [
    //             OtpTestData::ICON_SVG_DATA,
    //             'svg',
    //         ],
    //     ];
    // }

    // #[Test]
    // #[DataProvider('invalidIconResourceProvider')]
    // public function test_set_invalid_icon_ends_without_error($res, $ext)
    // {
    //     Storage::fake('imagesLink');
    //     Storage::fake('icons');

    //     $previousIcon = $this->customTotpTwofaccount->icon;
    //     $this->customTotpTwofaccount->setIcon($res, $ext);

    //     $this->assertEquals($previousIcon, $this->customTotpTwofaccount->icon);

    //     Storage::disk('icons')->assertMissing($this->customTotpTwofaccount->icon);
    //     Storage::disk('imagesLink')->assertMissing($this->customTotpTwofaccount->icon);
    // }

    // /**
    //  * Provide data for Icon store tests
    //  */
    // public static function invalidIconResourceProvider()
    // {
    //     return [
    //         'INVALID_PNG' => [
    //             'lkjdslfkjslkdfjlskdjflksjf',
    //             'png',
    //         ],
    //     ];
    // }
}
