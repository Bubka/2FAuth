<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\ReorderRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * ReorderRequestTest test class
 */
#[CoversClass(ReorderRequest::class)]
class ReorderRequestTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $request = new ReorderRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $request = new ReorderRequest;
        $request->merge($data);

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
                'orderedIds' => [1, 2, 5],
            ]],
            [[
                'orderedIds' => [5],
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request = new ReorderRequest;
        $request->merge($data);

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
                'orderedIds' => [], // required
            ]],
            [[
                'orderedIds' => null, // required
            ]],
            [[
                'orderedIds' => 0, // array
            ]],
            [[
                'orderedIds' => 'string', // array
            ]],
            [[
                'orderedIds' => true, // array
            ]],
        ];
    }
}
