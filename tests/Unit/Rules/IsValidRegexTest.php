<?php

namespace Tests\Unit\Rules;

use App\Rules\IsValidRegex;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * IsValidRegexTest test class
 */
#[CoversClass(IsValidRegex::class)]
class IsValidRegexTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $validator = Validator::make($data, ['value' => [new IsValidRegex]]);

        $this->assertFalse($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public static function provideValidData() : array
    {
        return [
            [[
                'value' => '^[\w\.=-]+@[\w\.-]+\.[\w]{2,3}$',
            ]],
            [[
                'value' => '^\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}$',
            ]],
            [[
                'value' => '\b([4]\d{3}[\s]\d{4}[\s]\d{4}[\s]\d{4}|[4]\d{3}[-]\d{4}[-]\d{4}[-]\d{4}|[4]\d{3}[.]\d{4}[.]\d{4}[.]\d{4}|[4]\d{3}\d{4}\d{4}\d{4})\b',
            ]],
            [[
                'value' => '(?i)\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))',
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $validator = Validator::make($data, ['value' => [new IsValidRegex]]);

        $this->assertTrue($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public static function provideInvalidData() : array
    {
        return [
            [[
                'value' => 'HOHI\\',
            ]],
        ];
    }
}
