<?php

namespace Tests\Unit;

use App\Helpers\Helpers;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * HelpersTest test class
 */
#[CoversClass(Helpers::class)]
class HelpersTest extends TestCase
{
    #[Test]
    #[DataProvider('versionNumberProvider')]
    public function test_cleanVersionNumber_returns_cleaned_version($dirtyVersion, $expected)
    {
        $cleanedVersion = Helpers::cleanVersionNumber($dirtyVersion);

        $this->assertEquals($expected, $cleanedVersion);
    }

    /**
     * Provide data for cleanVersionNumber() tests
     */
    public static function versionNumberProvider()
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

    #[Test]
    #[DataProvider('invalidVersionNumberProvider')]
    public function test_cleanVersionNumber_returns_false_with_invalid_semver($dirtyVersion)
    {
        $cleanedVersion = Helpers::cleanVersionNumber($dirtyVersion);

        $this->assertEquals(false, $cleanedVersion);
    }

    /**
     * Provide data for cleanVersionNumber() tests
     */
    public static function invalidVersionNumberProvider()
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

    #[Test]
    #[DataProvider('toBase32PaddedStringProvider')]
    public function test_toBase32Format_returns_base32_formated_string($str, $expected)
    {
        $base32str = Helpers::PadToBase32Format($str);

        $this->assertEquals($expected, $base32str);
    }

    /**
     * Provide data for cleanVersionNumber() tests
     */
    public static function toBase32PaddedStringProvider()
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

    #[Test]
    #[DataProvider('commaSeparatedToArrayProvider')]
    public function test_commaSeparatedToArray_returns_ids_in_array($str, $expected)
    {
        $array = Helpers::commaSeparatedToArray($str);

        $this->assertEquals($expected, $array);
    }

    /**
     * Provide data for cleanVersionNumber() tests
     */
    public static function commaSeparatedToArrayProvider()
    {
        return [
            'NOMINAL' => [
                '1,2,3',
                [1, 2, 3],
            ],
            'DUPLICATE' => [
                '1,2,2,3',
                [1, 2, 2, 3],
            ],
        ];
    }

    #[Test]
    #[DataProvider('invalidCommaSeparatedToArrayProvider')]
    public function test_commaSeparatedToArray_returns_unchanged_ids($str, $expected)
    {
        $array = Helpers::commaSeparatedToArray($str);

        $this->assertEquals($expected, $array);
    }

    /**
     * Provide data for cleanVersionNumber() tests
     */
    public static function invalidCommaSeparatedToArrayProvider()
    {
        return [
            'INVALID_IDS_LEADING_SPACES' => [
                '1, 2,3',
                '1, 2,3',
            ],
            'INVALID_IDS_TRAILING_SPACES' => [
                '1,2 ,3',
                '1,2 ,3',
            ],
            'INVALID_IDS_BAD_SEPARATOR' => [
                '1/2/3',
                '1/2/3',
            ],
            'INVALID_IDS_NOT_DIGIT' => [
                'a,b,c',
                'a,b,c',
            ],
            'INVALID_IDS_MISSING_DIGIT' => [
                '1,,3',
                '1,,3',
            ],
            'INVALID_IDS_LEADING_COMMA' => [
                ',2,3',
                ',2,3',
            ],
            'INVALID_IDS_TRAILING_COMMA' => [
                '1,2,',
                '1,2,',
            ],
            'NOT_STRING_BOOLEAN' => [
                true,
                true,
            ],
            'NOT_STRING_INT' => [
                1,
                1,
            ],
            'NOT_STRING_ARRAY' => [
                [1],
                [1],
            ],
            'NOT_STRING_NULL' => [
                null,
                null,
            ],
        ];
    }

    #[Test]
    public function test_lockedPreferences_returns_locked_preferences()
    {
        // See .env.testing which sets USERPREF_DEFAULT__THEME=light
        // while config/2fauth.php sets the default value to 'system' 
        $lockedPreferences = Helpers::lockedPreferences(config('2fauth.preferences'));

        $this->assertContains('theme', $lockedPreferences);
    }

    #[Test]
    public function test_lockedPreferences_returns_empty_array_when_empty_array_is_provided()
    {
        $param = [];

        $lockedPreferences = Helpers::lockedPreferences($param);

        $this->assertEquals([], $lockedPreferences);
    }

    #[Test]
    public function test_lockedPreferences_excludes_preference_when_env_var_is_empty()
    {
        // See .env.testing which sets USERPREF_LOCKED__DISPLAY_MODE=
        $lockedPreferences = Helpers::lockedPreferences(config('2fauth.preferences'));

        $this->assertNotContains('displayMode', $lockedPreferences);
    }

    #[Test]
    public function test_lockedPreferences_excludes_preference_when_env_var_is_malformed()
    {
        // See .env.testing which sets USERPREF_LOCKED___FORMAT_PASSWORD=false
        $lockedPreferences = Helpers::lockedPreferences(config('2fauth.preferences'));

        $this->assertNotContains('formatPassword', $lockedPreferences);
    }
}
