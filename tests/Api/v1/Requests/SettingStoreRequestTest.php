<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\SettingStoreRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tests\FeatureTestCase;

class SettingStoreRequestTest extends FeatureTestCase
{

    use WithoutMiddleware;

    /**
     * 
     */
    protected String $uniqueKey = 'UniqueKey';

    /**
     * @test
     */
    public function test_user_is_authorized()
    {   
        Auth::shouldReceive('check')
        ->once()
        ->andReturn(true);

        $request = new SettingStoreRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new SettingStoreRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Provide Valid data for validation test
     */
    public function provideValidData() : array
    {
        return [
            [[
                'key' => 'MyKey',
                'value' => true
            ]],
            [[
                'key' => 'MyKey',
                'value' => 'MyValue'
            ]],
            [[
                'key' => 'MyKey',
                'value' => 10
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $settingService = resolve('App\Services\SettingServiceInterface');
        $settingService->set($this->uniqueKey, 'uniqueValue');

        $request = new SettingStoreRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
    }

    /**
     * Provide invalid data for validation test
     */
    public function provideInvalidData() : array
    {
        return [
            [[
                'key' => null, // required
                'value' => ''
            ]],
            [[
                'key' => 'my-key', // alpha
                'value' => 'MyValue'
            ]],
            [[
                'key' => 10, // alpha
                'value' => 'MyValue'
            ]],
            [[
                'key' => 'mmmmmmoooooorrrrrreeeeeeettttttthhhhhhaaaaaaannnnnn128cccccchhhhhaaaaaarrrrrraaaaaaaccccccttttttttteeeeeeeeerrrrrrrrsssssss', // max:128
                'value' => 'MyValue'
            ]],
            [[
                'key' => $this->uniqueKey, // unique
                'value' => 'MyValue'
            ]],
        ];
    }

}