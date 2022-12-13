<?php

namespace Tests\Unit;

use App\Helpers\Helpers;
use Tests\TestCase;

/**
 * @covers \App\Helpers\Helpers
 */
class HelpersTest extends TestCase
{
    /**
     * @test
     * 
     * @dataProvider  versionNumberProvider
     */
    public function test_cleanVersionNumber_returns_cleaned_version($dirtyVersion, $expected)
    {
        $cleanedVersion = Helpers::cleanVersionNumber($dirtyVersion);

        $this->assertEquals($expected, $cleanedVersion);
    }

    /**
     * Provide data for cleanVersionNumber() tests
     */
    public function versionNumberProvider()
    {
        return [
            [
                'v3.2.1',
                '3.2.1',
            ],
            [
                'v3.2.1-beta',
                '3.2.1-beta',
            ],
            [
                'v3.0.1-alpha+001',
                '3.0.1-alpha+001',
            ],
            [
                'version03.0.1 alpha+001',
                '3.0.1',
            ],
        ];
    }

    /**
     * @test
     * 
     * @dataProvider  invalidVersionNumberProvider
     */
    public function test_cleanVersionNumber_returns_false_with_invalid_semver($dirtyVersion)
    {
        $cleanedVersion = Helpers::cleanVersionNumber($dirtyVersion);

        $this->assertEquals(false, $cleanedVersion);
    }

    /**
     * Provide data for cleanVersionNumber() tests
     */
    public function invalidVersionNumberProvider()
    {
        return [
            [
                'v3.2.',
            ],
            [
                'v3..1-beta',
            ],
            [
                'v.0.1-alpha+001',
            ],
            [
                '3.00.1 alpha+001',
            ],
            [
                '3.00.1 alpha+001',
            ],
        ];
    }

    /**
     * @test
     * 
     * @dataProvider  toBase32PaddedStringProvider
     */
    public function test_toBase32Format_returns_base32_formated_string($str, $expected)
    {
        $base32str = Helpers::PadToBase32Format($str);

        $this->assertEquals($expected, $base32str);
    }

    /**
     * Provide data for cleanVersionNumber() tests
     */
    public function toBase32PaddedStringProvider()
    {
        return [
            'SHORT_STRING' => [
                'eeee',
                'EEEE====',
            ],
            'LONG_STRING' => [
                'eeeezzzztt',
                'EEEEZZZZTT======',
            ],
            'EXACT_LENGTH_STRING' => [
                'eeeezzzz',
                'EEEEZZZZ',
            ],
            'EXACT_LONG_LENGTH_STRING' => [
                'eeeezzzzeeeezzzzeeeezzzz',
                'EEEEZZZZEEEEZZZZEEEEZZZZ',
            ],
            'NO_STRING' => [
                '',
                '',
            ],
            'BOOL_STRING' => [
                true,
                '1=======',
            ],
            'INT_STRING' => [
                10,
                '10======',
            ],
            'FLOAT_STRING' => [
                0.1,
                '0.1=====',
            ],
        ];
    }
}
