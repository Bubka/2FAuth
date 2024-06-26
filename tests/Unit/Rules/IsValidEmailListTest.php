<?php

namespace Tests\Unit\Rules;

use App\Rules\IsValidEmailList;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * IsValidEmailListTest test class
 */
#[CoversClass(IsValidEmailList::class)]
class IsValidEmailListTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $validator = Validator::make($data, ['value' => [new IsValidEmailList]]);

        $this->assertFalse($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public static function provideValidData() : array
    {
        return [
            [[
                'value' => 'johndoe@example.com',
            ]],
            [[
                'value' => 'johndoe@example.com|janedoe@example.com',
            ]],
            [[
                'value' => '|johndoe@example.com|janedoe@example.com',
            ]],
            [[
                'value' => 'johndoe@example.com|janedoe@example.com|',
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $validator = Validator::make($data, ['value' => [new IsValidEmailList]]);

        $this->assertTrue($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public static function provideInvalidData() : array
    {
        return [
            [[
                'value' => 'johndoeexamplecom',
            ]],
            [[
                'value' => 'johndoe@example.com|janedoeexamplecom',
            ]],
            [[
                'value' => 'johndoe@example.com,janedoe@example.com',
            ]],
            [[
                'value' => 'johndoe@example.com;janedoe@example.com|',
            ]],
            [[
                'value' => 'johndoe@example.com janedoe@example.com',
            ]],
            [[
                'value' => 'johndoe@example.com | janedoe@example.com',
            ]],
        ];
    }
}
