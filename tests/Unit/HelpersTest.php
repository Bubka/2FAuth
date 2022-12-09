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
     */
    public function test_getUniqueFilename_returns_filename()
    {
        $ext = 'jpg';
        $filename = Helpers::getUniqueFilename($ext);

        $this->assertIsString($filename);
        $this->assertStringEndsWith('.' . $ext, $filename);
        $this->assertEquals(41 + strlen($ext), strlen($filename));
    }

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
}
