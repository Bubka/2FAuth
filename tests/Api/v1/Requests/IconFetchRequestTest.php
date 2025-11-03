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
        $request = new IconFetchRequest;
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
            'VALID_SERVICE_AS_STRING' => [[
                'service' => 'validWord',
            ]],
            'VALID_SERVCE_WITH_SPECIAL_CHARS' => [[
                'service' => '~string.with-sp3ci@l-ch4rs',
            ]],
            'VALID_SELFH_ICON_COLLECTION' => [[
                'service'        => 'validWord',
                'iconCollection' => 'selfh',
            ]],
            'VALID_DASHBOARDICONS_ICON_COLLECTION' => [[
                'service'        => 'validWord',
                'iconCollection' => 'dashboardicons',
            ]],
            'VALID_TFA_ICON_COLLECTION' => [[
                'service'        => 'validWord',
                'iconCollection' => 'tfa',
            ]],
            'VALID_SELFH_ICON_COLLECTION_WITH_VALID_REGULAR_VARIANT' => [[
                'service'        => 'validWord',
                'iconCollection' => 'selfh',
                'variant'        => 'regular',
            ]],
            'VALID_SELFH_ICON_COLLECTION_WITH_VALID_LIGHT_VARIANT' => [[
                'service'        => 'validWord',
                'iconCollection' => 'selfh',
                'variant'        => 'light',
            ]],
            'VALID_SELFH_ICON_COLLECTION_WITH_VALID_DARK_VARIANT' => [[
                'service'        => 'validWord',
                'iconCollection' => 'selfh',
                'variant'        => 'dark',
            ]],
            'VALID_DASHBOARDICONS_ICON_COLLECTION_WITH_VALID_REGULAR_VARIANT' => [[
                'service'        => 'validWord',
                'iconCollection' => 'dashboardicons',
                'variant'        => 'regular',
            ]],
            'VALID_DASHBOARDICONS_ICON_COLLECTION_WITH_VALID_LIGHT_VARIANT' => [[
                'service'        => 'validWord',
                'iconCollection' => 'dashboardicons',
                'variant'        => 'light',
            ]],
            'VALID_DASHBOARDICONS_ICON_COLLECTION_WITH_VALID_DARK_VARIANT' => [[
                'service'        => 'validWord',
                'iconCollection' => 'dashboardicons',
                'variant'        => 'dark',
            ]],
            'VALID_TFA_ICON_COLLECTION_WITH_VALID_REGULAR_VARIANT' => [[
                'service'        => 'validWord',
                'iconCollection' => 'tfa',
                'variant'        => 'regular',
            ]],
        ];
    }

    #[Test]
    #[DataProvider('provideInvalidData')]
    public function test_invalid_data(array $data) : void
    {
        $request = new IconFetchRequest;
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
            'NULL_SERVICE' => [[
                'service' => null,
            ]],
            'NULL_ICON_COLLECTION' => [[
                'service'        => 'validWord',
                'iconCollection' => null,
            ]],
            'NULL_VARIANT' => [[
                'service'        => 'validWord',
                'iconCollection' => 'tfa',
                'variant'        => null,
            ]],
            'EMPTY_ICON_COLLECTION' => [[
                'service'        => 'validWord',
                'iconCollection' => '',
            ]],
            'EMPTY_VARIANT' => [[
                'service'        => 'validWord',
                'iconCollection' => 'tfa',
                'variant'        => '',
            ]],
            'SERVICE_AS_INT' => [[
                'service' => 0,
            ]],
            'SERVICE_AS_BOOL' => [[
                'service' => true,
            ]],
            'SERVICE_AS_ARRAY' => [[
                'service' => [],
            ]],
            'NOT_IN_ICON_COLLECTION_LIST' => [[
                'service'        => 'validWord',
                'iconCollection' => 'string_not_in_icon_collection_list',
            ]],
            'NOT_IN_SELFH_VARIANT_LIST' => [[
                'service'        => 'validWord',
                'iconCollection' => 'selfh',
                'variant'        => 'string_not_in_selfh_variant_list',
            ]],
            'NOT_IN_DASHBOARDICONS_VARIANT_LIST' => [[
                'service'        => 'validWord',
                'iconCollection' => 'dashboardicons',
                'variant'        => 'string_not_in_dashboardicons_variant_list',
            ]],
            'NOT_IN_TFA_VARIANT_LIST' => [[
                'service'        => 'validWord',
                'iconCollection' => 'tfa',
                'variant'        => 'string_not_in_tfa_variant_list',
            ]],
        ];
    }
}
