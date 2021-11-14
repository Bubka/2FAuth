<?php

namespace Tests\Api\v1\Requests;

use App\Group;
use App\Api\v1\Requests\GroupStoreRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tests\FeatureTestCase;

class GroupStoreRequestTest extends FeatureTestCase
{

    use WithoutMiddleware;

    /**
     * 
     */
    protected String $uniqueGroupName = 'MyGroup';

    /**
     * @test
     */
    public function test_user_is_authorized()
    {   
        Auth::shouldReceive('check')
        ->once()
        ->andReturn(true);

        $request = new GroupStoreRequest();
    
        $this->assertTrue($request->authorize());
    }

    /**
     * @dataProvider provideValidData
     */
    public function test_valid_data(array $data) : void
    {
        $request = new GroupStoreRequest();
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
                'name' => 'validWord'
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $group = new Group([
            'name' => $this->uniqueGroupName,
        ]);

        $group->save();

        $request = new GroupStoreRequest();
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
                'name' => '' // required
            ]],
            [[
                'name' => true // string
            ]],
            [[
                'name' => 8 // string
            ]],
            [[
                'name' => 'mmmmmmoooooorrrrrreeeeeeettttttthhhhhhaaaaaaannnnnn32cccccchhhhhaaaaaarrrrrrsssssss' // max:32
            ]],
            [[
                'name' => $this->uniqueGroupName // unique
            ]],
        ];
    }

}