<?php

namespace Tests\Feature\Http\Requests;

use App\Http\Requests\WebauthnRenameRequest;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * WebauthnRenameRequestTest test class
 */
#[CoversClass(WebauthnRenameRequest::class)]
class WebauthnRenameRequestTest extends TestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized()
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        Gate::shouldReceive('allows')
            ->with('manage-webauthn-credentials')
            ->once()
            ->andReturn(true);

        $request = new WebauthnRenameRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data) : void
    {
        $request = new WebauthnRenameRequest;
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
                'name' => 'Yubikey',
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request = new WebauthnRenameRequest;
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
                'name' => '', // required
            ]],
            [[
                'name' => true, // string
            ]],
            [[
                'name' => 0, // string
            ]],
        ];
    }
}
