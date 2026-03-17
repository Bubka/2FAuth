<?php

namespace Tests\Api\v1\Requests;

use App\Api\v1\Requests\TwoFAccountShareStoreRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(TwoFAccountShareStoreRequest::class)]
class TwoFAccountShareStoreRequestTest extends FeatureTestCase
{
    use WithoutMiddleware;

    #[Test]
    public function test_user_is_authorized() : void
    {
        Auth::shouldReceive('check')
            ->once()
            ->andReturn(true);

        $request = new TwoFAccountShareStoreRequest;

        $this->assertTrue($request->authorize());
    }

    #[Test]
    #[DataProvider('provideValidData')]
    public function test_valid_data(array $data, bool $usePrepareForValidation) : void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $request = $this->newRequest();
        $request->merge($this->replaceUserTokens($data, $userA->id, $userB->id));

        if ($usePrepareForValidation) {
            $request->callPrepareForValidation();
        }

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertFalse($validator->fails());
    }

    public static function provideValidData() : array
    {
        return [
            [[
                'ids' => ['USER_A_ID', 'USER_B_ID'],
            ], false],
            [[
                'user_id' => 'USER_A_ID',
            ], true],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $userA = User::factory()->create();

        $request = $this->newRequest();
        $request->merge($this->replaceUserTokens($data, $userA->id, null));
        $request->callPrepareForValidation();

        $validator = Validator::make($request->all(), $request->rules());

        $this->assertTrue($validator->fails());
    }

    public static function provideInvalidData() : array
    {
        return [
            [[
                'ids' => null,
            ]],
            [[
                'ids' => [],
            ]],
            [[
                'ids' => '1,2',
            ]],
            [[
                'ids' => ['USER_A_ID', 'USER_A_ID'],
            ]],
            [[
                'ids' => [99999999],
            ]],
            [[
                'user_id' => 99999999,
            ]],
        ];
    }

    #[Test]
    public function test_prepare_for_validation_normalizes_legacy_user_id() : void
    {
        $targetUser = User::factory()->create();

        $request = $this->newRequest();
        $request->merge([
            'user_id' => $targetUser->id,
        ]);
        $request->callPrepareForValidation();

        $this->assertEquals([$targetUser->id], $request->input('ids'));
    }

    #[Test]
    public function test_prepare_for_validation_does_not_override_ids_when_both_inputs_are_present() : void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $request = $this->newRequest();
        $request->merge([
            'user_id' => $userA->id,
            'ids' => [$userB->id],
        ]);
        $request->callPrepareForValidation();

        $this->assertEquals([$userB->id], $request->input('ids'));
    }

    private function newRequest() : TwoFAccountShareStoreRequest
    {
        return new class extends TwoFAccountShareStoreRequest
        {
            public function callPrepareForValidation() : void
            {
                $this->prepareForValidation();
            }
        };
    }

    private function replaceUserTokens(array $data, int $userAId, ?int $userBId) : array
    {
        $encoded = json_encode($data);

        if (! is_string($encoded)) {
            return $data;
        }

        $encoded = str_replace('"USER_A_ID"', (string) $userAId, $encoded);
        if ($userBId !== null) {
            $encoded = str_replace('"USER_B_ID"', (string) $userBId, $encoded);
        }

        $decoded = json_decode($encoded, true);

        return is_array($decoded) ? $decoded : $data;
    }
}
