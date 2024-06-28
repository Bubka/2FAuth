<?php declare(strict_types=1);

namespace Tests\Api\v1\Requests\DataProviders;

final class TwoFAccountDataProvider
{
    /**
     * 
     */
    public static function validIdsProvider(): array
    {
        return [
            [[
                'ids' => '1',
            ]],
            [[
                'ids' => '1,2,5',
            ]],
        ];
    }

    /**
     * 
     */
    public static function invalidIdsProvider(): array
    {
        return [
            [[
                'ids' => '', // required
            ]],
            [[
                'ids' => null, // required
            ]],
            [[
                'ids' => true, // string
            ]],
            [[
                'ids' => 10, // string
            ]],
            [[
                'ids' => 'notaCommaSeparatedList', // regex
            ]],
            [[
                'ids' => 'a,b', // regex
            ]],
            [[
                'ids' => 'a,1', // regex
            ]],
            [[
                'ids' => ',1,2', // regex
            ]],
            [[
                'ids' => '1,,2', // regex
            ]],
            [[
                'ids' => '1,2,', // regex
            ]],
            [[
                'ids' => ',1,2,', // regex
            ]],
            [[
                'ids' => '1;2', // regex
            ]],
        ];
    }

    /**
     * 
     */
    public static function validIsAdminProvider(): array
    {
        return [
            [[
                'is_admin' => true,
            ]],
            [[
                'is_admin' => false,
            ]],
            [[
                'is_admin' => 0,
            ]],
            [[
                'is_admin' => 1,
            ]],
        ];
    }

    /**
     * 
     */
    public static function invalidIsAdminProvider(): array
    {
        return [
            [[
                'is_admin' => [],
            ]],
            [[
                'is_admin' => null,
            ]],
            [[
                'is_admin' => 'string',
            ]],
            [[
                'is_admin' => '',
            ]],
            [[
                'is_admin' => 5,
            ]],
        ];
    }
}