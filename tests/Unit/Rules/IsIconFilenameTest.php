<?php

namespace Tests\Unit\Rules;

use App\Rules\IsIconFilename;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\TestCase;

/**
 * IsIconFilenameTest test class
 */
#[CoversClass(IsIconFilename::class)]
class IsIconFilenameTest extends TestCase
{
    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(string $value) : void
    {
        $validator = Validator::make(['value' => $value], ['value' => [new IsIconFilename]]);

        $this->assertFalse($validator->fails());
    }

    /**
     * Data provider for valid icon filenames
     */
    public static function provideValidData() : array
    {
        return [
            [OtpTestData::ICON_PNG],
            [OtpTestData::ICON_JPEG],
            [OtpTestData::ICON_WEBP],
            [OtpTestData::ICON_BMP],
            [OtpTestData::ICON_SVG],
            ['Zk3Jd0Qp9aVv1mX7uYtE4nBcL2sHgF6wRoKiT5Ae.png'],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(mixed $value) : void
    {
        $validator = Validator::make(['value' => $value], ['value' => [new IsIconFilename]]);

        $this->assertTrue($validator->fails());
    }

    /**
     * Data provider for invalid icon filenames
     */
    public static function provideInvalidData() : array
    {
        return [
            ['../../../../../local.sqlite'],
            ['../icon.png'],
            ['sub/icon.png'],
            ['sub\\icon.png'],
            ['icon.php'],
            ['icon.tiff'],
            ['icon'],
            ['.png'],
            ["icon.png\0"],
            [123],
        ];
    }
}
