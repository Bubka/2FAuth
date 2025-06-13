<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\SettingStoreRequest;
use App\Facades\Settings;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * SettingStoreRequestTest test class
 */
#[CoversClass(SettingStoreRequest::class)]
class SettingStoreRequestTest extends FeatureTestCase
{
    use WithoutMiddleware;

    const UNIQUE_KEY = 'UniqueKey';

    #[Test]
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $request = new SettingStoreRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $request = new SettingStoreRequest;
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
                'key'   => 'MyKey',
                'value' => true,
            ]],
            [[
                'key'   => 'MyKey',
                'value' => 'MyValue',
            ]],
            [[
                'key'   => 'MyKey',
                'value' => 10,
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        Settings::set(self::UNIQUE_KEY, 'uniqueValue');

        $request = new SettingStoreRequest;
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
                'key'   => null, // required
                'value' => '',
            ]],
            [[
                'key'   => 'my-key', // alpha
                'value' => 'MyValue',
            ]],
            [[
                'key'   => 10, // alpha
                'value' => 'MyValue',
            ]],
            [[
                'key'   => 'mmmmmmoooooorrrrrreeeeeeettttttthhhhhhaaaaaaannnnnn128cccccchhhhhaaaaaarrrrrraaaaaaaccccccttttttttteeeeeeeeerrrrrrrrsssssss', // max:128
                'value' => 'MyValue',
            ]],
            [[
                'key'   => self::UNIQUE_KEY, // unique
                'value' => 'MyValue',
            ]],
        ];
    }
}
