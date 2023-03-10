<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\GroupStoreRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\FeatureTestCase;

/**
 * @covers \App\Api\v1\Requests\GroupStoreRequest
 */
class GroupStoreRequestTest extends FeatureTestCase
{
    use WithoutMiddleware;

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected String $uniqueGroupName = 'MyGroup';

    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

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
        $request = Mockery::mock(GroupStoreRequest::class)->makePartial();
        $request->shouldReceive('user')
            ->andReturn($this->user);

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
                'name' => 'validWord',
            ]],
        ];
    }

    /**
     * @dataProvider provideInvalidData
     */
    public function test_invalid_data(array $data) : void
    {
        $group = Group::factory()->for($this->user)->create([
            'name' => $this->uniqueGroupName,
        ]);

        $request = Mockery::mock(GroupStoreRequest::class)->makePartial();
        $request->shouldReceive('user')
            ->andReturn($this->user);

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
                'name' => '', // required
            ]],
            [[
                'name' => true, // string
            ]],
            [[
                'name' => 8, // string
            ]],
            [[
                'name' => 'mmmmmmoooooorrrrrreeeeeeettttttthhhhhhaaaaaaannnnnn32cccccchhhhhaaaaaarrrrrrsssssss', // max:32
            ]],
            [[
                'name' => $this->uniqueGroupName, // unique
            ]],
        ];
    }
}
