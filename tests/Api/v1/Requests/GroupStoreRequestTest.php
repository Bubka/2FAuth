<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\GroupStoreRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * GroupStoreRequestTest test class
 */
#[CoversClass(GroupStoreRequest::class)]
class GroupStoreRequestTest extends FeatureTestCase
{
    use WithoutMiddleware;

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    const UNIQUE_GROUP_NAME = 'MyGroup';

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $request = new GroupStoreRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
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
    public static function provideValidData() : array
    {
        return [
            [[
                'name' => 'validWord',
            ]],
            [[
                'name' => 'valid Word',
            ]],
            [[
                'name' => 'valid_Word',
            ]],
            [[
                'name' => 'valid-Word',
            ]],
            [[
                'name' => 'valid\'Word',
            ]],
            [[
                'name' => 'vàlîdWörd',
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $group = Group::factory()->for($this->user)->create([
            'name' => self::UNIQUE_GROUP_NAME,
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
    public static function provideInvalidData() : array
    {
        return [
            [[
                'name' => '', // required
            ]],
            [[
                'name' => true, // string
            ]],
            [[
                'name' => 'mmmmmmoooooorrrrrreeeeeeettttttthhhhhhaaaaaaannnnnn32cccccchhhhhaaaaaarrrrrrsssssss', // max:32
            ]],
            [[
                'name' => self::UNIQUE_GROUP_NAME, // unique
            ]],
            [[
                'name' => 'valid"Word', // special char
            ]],
        ];
    }
}
