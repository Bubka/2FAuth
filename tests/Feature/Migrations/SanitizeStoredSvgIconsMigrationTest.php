<?php

namespace Tests\Feature\Migrations;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * SanitizeStoredSvgIconsMigrationTest test class
 */
class SanitizeStoredSvgIconsMigrationTest extends FeatureTestCase
{
    protected const INVALID_SVG_DATA = 'this_will_make_svg_data_invalid' . OtpTestData::ICON_SVG_DATA;

    protected Migration $migration;

    protected function setUp() : void
    {
        parent::setUp();

        Storage::fake('icons');
        $this->migration = require database_path('migrations/2026_11_14_000000_sanitize_stored_svg_icons.php');
    }

    #[Test]
    public function test_up_sanitizes_infected_svg_on_disk()
    {
        Storage::disk('icons')->put(OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA_INFECTED);

        $this->migration->up();

        $this->assertStringNotContainsString(
            OtpTestData::ICON_SVG_MALICIOUS_CODE,
            Storage::disk('icons')->get(OtpTestData::ICON_SVG)
        );
    }

    #[Test]
    public function test_up_leaves_clean_svg_and_other_icons_untouched()
    {
        $png = base64_decode(OtpTestData::ICON_PNG_DATA);

        Storage::disk('icons')->put(OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA);
        Storage::disk('icons')->put(OtpTestData::ICON_PNG, $png);

        $this->migration->up();

        $this->assertStringContainsString('<circle', Storage::disk('icons')->get(OtpTestData::ICON_SVG));
        $this->assertSame($png, Storage::disk('icons')->get(OtpTestData::ICON_PNG));
    }

    #[Test]
    public function test_up_sanitizes_infected_svg_in_database()
    {
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_SVG,
            'content' => base64_encode(OtpTestData::ICON_SVG_DATA_INFECTED),
        ]);

        $this->migration->up();

        $this->assertStringNotContainsString(
            OtpTestData::ICON_SVG_MALICIOUS_CODE,
            base64_decode(DB::table('icons')->where('name', OtpTestData::ICON_SVG)->value('content'))
        );
    }

    #[Test]
    public function test_up_removes_unsanitizable_svg_and_detaches_it_from_accounts()
    {
        $user = User::factory()->create();

        TwoFAccount::factory()->for($user)->create(['icon' => OtpTestData::ICON_SVG]);
        Storage::disk('icons')->put(OtpTestData::ICON_SVG, self::INVALID_SVG_DATA);
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_SVG,
            'content' => base64_encode(self::INVALID_SVG_DATA),
        ]);

        $this->migration->up();

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_SVG);
        $this->assertDatabaseMissing('icons', ['name' => OtpTestData::ICON_SVG]);
        $this->assertDatabaseMissing('twofaccounts', ['icon' => OtpTestData::ICON_SVG]);
    }
}
