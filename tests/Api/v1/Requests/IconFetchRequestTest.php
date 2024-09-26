<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\IconFetchRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * IconFetchRequestTest test class
 */
#[CoversClass(IconFetchRequest::class)]
class IconFetchRequestTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $request = new IconFetchRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $request   = new IconFetchRequest;
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public static function provideValidData() : array
    {
        return [
            [[
                'service' => 'validWord',
            ]],
            [[
                'service' => '0',
            ]],
            [[
                'service' => '~string.with-sp3ci@l-ch4rs',
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request   = new IconFetchRequest;
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
    }

    /**
     * Provide invalid data for validation test
     */
    public static function provideInvalidData() : array
    {
        return [
            [[
                'service' => null,
            ]],
            [[
                'service' => 0,
            ]],
            [[
                'service' => true,
            ]],
            [[
                'service' => [],
            ]],
        ];
    }
}
