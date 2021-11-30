<?php

namespace Tests\Feature\Services;

use Tests\FeatureTestCase;
use Tests\Classes\LocalFile;
use Illuminate\Support\Facades\DB;


/**
 * @covers \App\Services\QrCodeService
 */
class QrCodeServiceTest extends FeatureTestCase
{
    /**
     * App\Services\QrCodeService $qrcodeService
     */
    protected $qrcodeService;

    private const STRING_TO_ENCODE = 'stringToEncode';
    private const STRING_ENCODED = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAIAAAAiOjnJAAAABnRSTlMA/wD/AP83WBt9AAAACXBIWXMAAA7EAAAOxAGVKw4bAAADOklEQVR4nO3dMW7kMBAAwdPh/v/ldeBLFRCYBkm7KveaEBoMBhT1fD6fPzDt7+4F8DMJi4SwSAiLhLBICIuEsEgIi4SwSAiLhLBICIuEsEgIi4SwSPxb/YPneYp1jHs7Z7a6/tXzam+/P7WeXVafgx2LhLBICIuEsEgIi4SwSAiLxPIc682u9xOn5lKnzZNueZ5v7FgkhEVCWCSERUJYJIRFQlgkxuZYb6bmIlNznanzUrfPmer127FICIuEsEgIi4SwSAiLhLBI5HOs290y3zqNHYuEsEgIi4SwSAiLhLBICIuEOdZ/p71XeDs7FglhkRAWCWGREBYJYZEQFol8jnXa+aTT1rPqlvXbsUgIi4SwSAiLhLBICIuEsEiMzbFuOc9U34819b7hLc/zjR2LhLBICIuEsEgIi4SwSAiLxHPL+Z7a1HcP+WbHIiEsEsIiISwSwiIhLBLCIrE8x6rPCU2tpz5f9eaWc1r1Ou1YJIRFQlgkhEVCWCSERUJYJPLzWKd912/Xuavb3xM0x+IIwiIhLBLCIiEsEsIiISwSy/dj7ZpL1fOn+nxS/X/rc2ar7FgkhEVCWCSERUJYJIRFQlgk8u8VrjrtPb4p9VxtVf37diwSwiIhLBLCIiEsEsIiISwS+f1Yu+6dmlKvf8ppczs7FglhkRAWCWGREBYJYZEQFolf973C0+5bX7XrPcRVdiwSwiIhLBLCIiEsEsIiISwSx32vcMqu+9lP+7+7zmnZsUgIi4SwSAiLhLBICIuEsEiM3Y91y/cH699Z/f2357Y6l6qfg+8VcgRhkRAWCWGREBYJYZEQFon8nvf6XvJd6vNP9fcHvVfIlYRFQlgkhEVCWCSERUJYJI77XmHttPvQp9az6x78N3YsEsIiISwSwiIhLBLCIiEsEr9ujjXltHvCTvt+oh2LhLBICIuEsEgIi4SwSAiLRD7HOu19wDf1veqn3V9V/74di4SwSAiLhLBICIuEsEgIi8TYHOu080mrpt7LO+3+qqn75VfZsUgIi4SwSAiLhLBICIuEsEg8t5yX4i52LBLCIiEsEsIiISwSwiIhLBLCIiEsEsIiISwSwiIhLBLCIiEsEl+BmgChoSs9XAAAAABJRU5ErkJggg==';
    private const DECODED_IMAGE = 'otpauth://totp/test@test.com?secret=A4GRFHVIRBGY7UIW';

    
    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->qrcodeService = $this->app->make('App\Services\QrCodeService');
    }


    /**
     * @test
     */
    public function test_encode_returns_correct_value()
    {
        // $rendered = $this->qrcodeService->encode(self::STRING_TO_ENCODE);
        $this->assertEquals(self::STRING_ENCODED, $this->qrcodeService->encode(self::STRING_TO_ENCODE));
    }


    /**
     * @test
     */
    public function test_decode_valid_image_returns_correct_value()
    {
        $file = LocalFile::fake()->validQrcode();

        $this->assertEquals(self::DECODED_IMAGE, $this->qrcodeService->decode($file));
    }


    /**
     * @test
     */
    public function test_decode_invalid_image_returns_correct_value()
    {
        $this->expectException(\App\Exceptions\InvalidQrCodeException::class);

        $this->qrcodeService->decode(LocalFile::fake()->invalidQrcode());
    }

}