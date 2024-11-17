<?php

namespace Tests\Feature\Services;

use App\Exceptions\FailedIconStoreDatabaseTogglingException;
use App\Facades\Settings;
use App\Models\Icon;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Services\IconStoreService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * IconStoreTest test class
 */
#[CoversClass(IconStoreService::class)]
class IconStoreServiceTest extends FeatureTestCase
{
    use WithoutMiddleware;

    /**
     * The IconStore under test
     */
    protected IconStoreService $iconStore;

    public function setUp() : void
    {
        parent::setUp();

        Storage::fake('icons');
        $this->iconStore = $this->app->make(IconStoreService::class);
    }

    #[Test]
    public function test_get_returns_icon_content()
    {
        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));

        $content = $this->iconStore->get(OtpTestData::ICON_PNG);

        $this->assertSame(OtpTestData::ICON_PNG_DATA, base64_encode($content));
    }

    #[Test]
    public function test_get_returns_null_when_icon_is_missing()
    {
        $this->assertNull($this->iconStore->get(OtpTestData::ICON_PNG));
    }

    #[Test]
    public function test_get_returns_content_when_icon_is_missing_on_disk_but_exists_in_db()
    {
        Settings::set('storeIconsInDatabase', true);

        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);

        $content = $this->iconStore->get(OtpTestData::ICON_PNG);

        $this->assertSame(OtpTestData::ICON_PNG_DATA, base64_encode($content));
    }

    #[Test]
    public function test_get_returns_null_when_nothing_is_requested()
    {
        $this->assertNull($this->iconStore->get(''));
    }

    #[Test]
    #[DataProvider('supportedMimeTypesProvider')]
    public function test_mimeType_returns_correct_mimetype($name, $base64content, $expected)
    {
        Storage::disk('icons')->put($name, $base64content);

        $mimeType = $this->iconStore->mimeType($name);

        $this->assertStringContainsStringIgnoringCase($mimeType, $expected);
    }

    /**
     * Provide data for index tests
     */
    public static function supportedMimeTypesProvider()
    {
        return [
            'PNG' => [
                OtpTestData::ICON_PNG,
                OtpTestData::ICON_PNG_DATA,
                'image/png',
            ],
            'JPEG' => [
                OtpTestData::ICON_JPEG,
                OtpTestData::ICON_JPEG_DATA,
                'image/jpeg',
            ],
            'WEBP' => [
                OtpTestData::ICON_WEBP,
                OtpTestData::ICON_WEBP_DATA,
                'image/webp',
            ],
            'BPM' => [
                OtpTestData::ICON_BMP,
                OtpTestData::ICON_BMP_DATA,
                'image/bmp|image/x-ms-bmp',
            ],
            'SVG' => [
                OtpTestData::ICON_SVG,
                OtpTestData::ICON_SVG_DATA_ENCODED,
                'image/svg+xml',
            ],
        ];
    }

    #[Test]
    public function test_mimeType_returns_correct_mimetype_of_fake_icon()
    {
        Storage::disk('icons')->put(OtpTestData::ICON_JPEG, base64_decode(OtpTestData::ICON_PNG_DATA));

        $mimeType = $this->iconStore->mimeType(OtpTestData::ICON_JPEG);

        $this->assertSame('image/png', $mimeType);
    }

    #[Test]
    public function test_mimeType_returns_null_when_icon_is_missing()
    {
        $this->assertFalse($this->iconStore->mimeType(OtpTestData::ICON_PNG));
    }

    #[Test]
    public function test_mimeType_returns_null_when_nothing_is_requested()
    {
        $this->assertFalse($this->iconStore->mimeType(''));
    }

    #[Test]
    public function test_clear_deletes_all_icons_and_returns_true()
    {
        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_JPEG, base64_decode(OtpTestData::ICON_JPEG_DATA));

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);

        $result = $this->iconStore->clear();

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_JPEG);
        $this->assertEmpty(Storage::disk('icons')->allFiles());
        $this->assertTrue($result);
    }

    #[Test]
    public function test_clear_deletes_all_icons_in_database_and_returns_true()
    {
        Settings::set('storeIconsInDatabase', true);

        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_JPEG, base64_decode(OtpTestData::ICON_JPEG_DATA));
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_JPEG,
            'content' => OtpTestData::ICON_JPEG_DATA,
        ]);

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_JPEG,
        ]);

        $result = $this->iconStore->clear();

        $this->assertEmpty(Storage::disk('icons')->allFiles());
        $this->assertDatabaseEmpty('icons');
        $this->assertTrue($result);
    }

    #[Test]
    public function test_clear_empty_disk_returns_true()
    {
        $this->assertTrue($this->iconStore->clear());
        $this->assertEmpty(Storage::disk('icons')->allFiles());
    }

    #[Test]
    public function test_clear_empty_disk_deletes_icons_in_database()
    {
        Settings::set('storeIconsInDatabase', true);

        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_JPEG,
            'content' => OtpTestData::ICON_JPEG_DATA,
        ]);

        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_JPEG,
        ]);

        $result = $this->iconStore->clear();

        $this->assertDatabaseEmpty('icons');
        $this->assertTrue($result);
    }

    #[Test]
    public function test_clear_deletes_only_supported_image_format_in_disk()
    {
        Storage::disk('icons')->put(OtpTestData::ICON_GIF, base64_decode(OtpTestData::ICON_GIF_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_JPEG, base64_decode(OtpTestData::ICON_JPEG_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_BMP, base64_decode(OtpTestData::ICON_BMP_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA);
        Storage::disk('icons')->put(OtpTestData::ICON_WEBP, base64_decode(OtpTestData::ICON_WEBP_DATA));

        Storage::disk('icons')->assertExists(OtpTestData::ICON_GIF);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_BMP);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_SVG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_WEBP);

        $this->iconStore->clear();

        Storage::disk('icons')->assertExists(OtpTestData::ICON_GIF);
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_JPEG);
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_BMP);
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_SVG);
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_WEBP);
    }

    #[Test]
    public function test_delete_deletes_provided_icon_and_returns_true()
    {
        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);

        $this->iconStore->delete(OtpTestData::ICON_PNG);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);
    }

    #[Test]
    public function test_delete_deletes_provided_icon_in_database_and_returns_true()
    {
        Settings::set('storeIconsInDatabase', true);

        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);

        $this->iconStore->delete(OtpTestData::ICON_PNG);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);
        $this->assertDatabaseMissing('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);
    }

    #[Test]
    public function test_delete_deletes_provided_icon_in_database_when_disk_is_empty()
    {
        Settings::set('storeIconsInDatabase', true);

        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);

        $this->iconStore->delete(OtpTestData::ICON_PNG);

        $this->assertDatabaseMissing('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);
    }

    #[Test]
    public function test_delete_deletes_provided_icons_and_returns_true()
    {
        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_JPEG, base64_decode(OtpTestData::ICON_JPEG_DATA));

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);

        $this->iconStore->delete([
            OtpTestData::ICON_PNG,
            OtpTestData::ICON_JPEG,
        ]);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_JPEG);
    }

    #[Test]
    public function test_delete_deletes_provided_icons_in_database_and_returns_true()
    {
        Settings::set('storeIconsInDatabase', true);

        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_JPEG, base64_decode(OtpTestData::ICON_JPEG_DATA));
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_JPEG,
            'content' => OtpTestData::ICON_JPEG_DATA,
        ]);

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_JPEG,
        ]);

        $this->iconStore->delete([
            OtpTestData::ICON_PNG,
            OtpTestData::ICON_JPEG,
        ]);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_JPEG);
        $this->assertDatabaseMissing('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);
        $this->assertDatabaseMissing('icons', [
            'name' => OtpTestData::ICON_JPEG,
        ]);
    }

    #[Test]
    public function test_delete_deletes_provided_icons_in_database_when_disk_is_empty()
    {
        Settings::set('storeIconsInDatabase', true);

        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_JPEG,
            'content' => OtpTestData::ICON_JPEG_DATA,
        ]);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_JPEG);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_JPEG,
        ]);

        $this->iconStore->delete([
            OtpTestData::ICON_PNG,
            OtpTestData::ICON_JPEG,
        ]);

        $this->assertDatabaseMissing('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);
        $this->assertDatabaseMissing('icons', [
            'name' => OtpTestData::ICON_JPEG,
        ]);
    }

    #[Test]
    public function test_delete_missing_icon_returns_true()
    {
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);

        $result = $this->iconStore->delete(OtpTestData::ICON_PNG);

        $this->assertTrue($result);
    }

    #[Test]
    public function test_delete_empty_icons_returns_true()
    {
        $result = $this->iconStore->delete([]);

        $this->assertTrue($result);
    }

    #[Test]
    public function test_delete_returns_false_when_it_fails()
    {
        Storage::fake('icons');
        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));

        Storage::shouldReceive('disk->delete')
            ->andReturn(false);

        $result = $this->iconStore->delete(OtpTestData::ICON_PNG);

        $this->assertFalse($result);
    }

    #[Test]
    public function test_store_writes_the_icon_to_disk_and_returns_true()
    {
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);

        $result = $this->iconStore->store(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));

        $this->assertTrue($result);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
    }

    #[Test]
    public function test_store_writes_the_icon_to_disk_and_database_and_returns_true()
    {
        Settings::set('storeIconsInDatabase', true);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);
        $this->assertDatabaseMissing('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);

        $result = $this->iconStore->store(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));

        $this->assertTrue($result);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        $this->assertDatabaseHas('icons', [
            'name' => OtpTestData::ICON_PNG,
        ]);
    }

    #[Test]
    public function test_store_returns_false_when_it_fails()
    {
        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);

        $iconName    = OtpTestData::ICON_PNG;
        $iconContent = '';

        Storage::shouldReceive('disk->put')
            ->with($iconName, $iconContent)
            ->andReturn(false);

        Storage::shouldReceive('disk->mimeType')
            ->with($iconName)
            ->andReturn('image/png');

        $result = $this->iconStore->store($iconName, $iconContent);

        $this->assertFalse($result);
    }

    #[Test]
    public function test_store_stores_sanitized_svg_content()
    {
        Settings::set('storeIconsInDatabase', true);

        $result = $this->iconStore->store(OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA_INFECTED);

        $this->assertTrue($result);

        $this->assertStringNotContainsString(
            OtpTestData::ICON_SVG_MALICIOUS_CODE,
            Storage::disk('icons')->get(OtpTestData::ICON_SVG)
        );

        $dbRecord = DB::table('icons')->where('name', OtpTestData::ICON_SVG)->first();

        $this->assertStringNotContainsString(
            OtpTestData::ICON_SVG_MALICIOUS_CODE,
            $dbRecord->content,
        );
    }

    #[Test]
    public function test_store_returns_false_when_svg_sanitize_failed()
    {
        $result = $this->iconStore->store(OtpTestData::ICON_SVG, 'this_will_make_svg_data_invalid' . OtpTestData::ICON_SVG_DATA);

        $this->assertFalse($result);
    }

    #[Test]
    public function test_store_deletes_svg_icon_that_cannot_be_sanitized()
    {
        Settings::set('storeIconsInDatabase', true);

        $result = $this->iconStore->store(OtpTestData::ICON_SVG, 'this_will_make_svg_data_invalid' . OtpTestData::ICON_SVG_DATA);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_SVG);
        $this->assertDatabaseMissing('icons', [
            'name' => OtpTestData::ICON_SVG,
        ]);
    }

    #[Test]
    public function test_exists_returns_true()
    {
        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));

        $this->assertTrue($this->iconStore->exists(OtpTestData::ICON_PNG));
    }

    #[Test]
    public function test_exists_fixes_missing_file_in_disk()
    {
        Settings::set('storeIconsInDatabase', true);

        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);

        $this->iconStore->exists(OtpTestData::ICON_PNG);

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
    }

    #[Test]
    public function test_exists_returns_false()
    {
        $this->assertFalse($this->iconStore->exists(OtpTestData::ICON_PNG));
    }

    #[Test]
    public function test_setDatabaseReplication_stores_icon_files_to_database()
    {
        $user  = User::factory()->create();
        $admin = User::factory()->administrator()->create();

        TwoFAccount::factory()->for($user)->create([
            'icon' => OtpTestData::ICON_PNG,
        ]);
        TwoFAccount::factory()->for($admin)->create([
            'icon' => OtpTestData::ICON_JPEG,
        ]);

        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_JPEG, base64_decode(OtpTestData::ICON_JPEG_DATA));

        Settings::set('storeIconsInDatabase', true);

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);

        $this->assertDatabaseHas('icons', [
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);
        $this->assertDatabaseHas('icons', [
            'name'    => OtpTestData::ICON_JPEG,
            'content' => OtpTestData::ICON_JPEG_DATA,
        ]);
        $this->assertDatabaseCount('icons', 2);
    }

    #[Test]
    public function test_setDatabaseReplication_stores_only_registered_icon_to_database()
    {
        $user = User::factory()->create();

        TwoFAccount::factory()->for($user)->create([
            'icon' => OtpTestData::ICON_PNG,
        ]);

        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_JPEG, base64_decode(OtpTestData::ICON_JPEG_DATA));

        Settings::set('storeIconsInDatabase', true);

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);

        $this->assertDatabaseHas('icons', [
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);
        $this->assertDatabaseMissing('icons', [
            'name'    => OtpTestData::ICON_JPEG,
            'content' => OtpTestData::ICON_JPEG_DATA,
        ]);
        $this->assertDatabaseCount('icons', 1);
    }

    #[Test]
    public function test_setDatabaseReplication_clears_database_before_replication()
    {
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);

        Settings::set('storeIconsInDatabase', true);

        $this->assertDatabaseEmpty('icons');
    }

    #[Test]
    public function test_setDatabaseReplication_skips_icon_when_file_is_missing()
    {
        $user = User::factory()->create();

        TwoFAccount::factory()->for($user)->create([
            'icon' => OtpTestData::ICON_PNG,
        ]);

        Storage::disk('icons')->assertMissing(OtpTestData::ICON_PNG);

        Settings::set('storeIconsInDatabase', true);

        $this->assertDatabaseEmpty('icons');
    }

    #[Test]
    public function test_setDatabaseReplication_restores_icons_as_file_and_clears_database()
    {
        Settings::set('storeIconsInDatabase', true);

        $user  = User::factory()->create();
        $admin = User::factory()->administrator()->create();

        TwoFAccount::factory()->for($user)->create([
            'icon' => OtpTestData::ICON_PNG,
        ]);
        TwoFAccount::factory()->for($admin)->create([
            'icon' => OtpTestData::ICON_JPEG,
        ]);

        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_JPEG,
            'content' => OtpTestData::ICON_JPEG_DATA,
        ]);

        Settings::set('storeIconsInDatabase', false);

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);

        $this->assertDatabaseEmpty('icons');
    }

    #[Test]
    public function test_setDatabaseReplication_overrides_existing_files_during_restoration_from_database()
    {
        Settings::set('storeIconsInDatabase', true);

        $user = User::factory()->create();

        TwoFAccount::factory()->for($user)->create([
            'icon' => OtpTestData::ICON_PNG,
        ]);

        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);

        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_JPEG_DATA));

        Settings::set('storeIconsInDatabase', false);

        $this->assertEquals(base64_decode(OtpTestData::ICON_PNG_DATA), Storage::disk('icons')->get(OtpTestData::ICON_PNG));
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_setDatabaseReplication_On_sends_exception_and_does_nothing_if_filling_database_fails()
    {
        $user  = User::factory()->create();
        $admin = User::factory()->administrator()->create();

        TwoFAccount::factory()->for($user)->create([
            'icon' => OtpTestData::ICON_PNG,
        ]);
        TwoFAccount::factory()->for($admin)->create([
            'icon' => OtpTestData::ICON_JPEG,
        ]);

        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));
        Storage::disk('icons')->put(OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_JPEG_DATA));

        $this->expectException(FailedIconStoreDatabaseTogglingException::class);

        $mock = $this->mock('overload:' . Icon::class, function (MockInterface $iconModel) {
            $iconModel->shouldReceive('truncate')
                ->andReturn(true);
            $iconModel->shouldReceive('firstOrNew')
                ->andThrow(new \Exception);
        });

        Settings::set('storeIconsInDatabase', true);

        $this->assertDatabaseEmpty('icons');
        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);
    }

    #[Test]
    public function test_setDatabaseReplication_Off_sends_exception_and_does_nothing_if_filling_database_fails()
    {
        Settings::set('storeIconsInDatabase', true);

        $user  = User::factory()->create();
        $admin = User::factory()->administrator()->create();

        TwoFAccount::factory()->for($user)->create([
            'icon' => OtpTestData::ICON_PNG,
        ]);
        TwoFAccount::factory()->for($admin)->create([
            'icon' => OtpTestData::ICON_JPEG,
        ]);

        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);
        DB::table('icons')->insert([
            'name'    => OtpTestData::ICON_JPEG,
            'content' => OtpTestData::ICON_JPEG_DATA,
        ]);

        Storage::shouldReceive('disk->put')
            ->once()
            ->andThrow(new FailedIconStoreDatabaseTogglingException);

        $this->expectException(FailedIconStoreDatabaseTogglingException::class);

        Settings::set('storeIconsInDatabase', false);

        Storage::disk('icons')->assertExists(OtpTestData::ICON_PNG);
        Storage::disk('icons')->assertExists(OtpTestData::ICON_JPEG);

        $this->assertDatabaseHas('icons', [
            'name'    => OtpTestData::ICON_PNG,
            'content' => OtpTestData::ICON_PNG_DATA,
        ]);
        $this->assertDatabaseHas('icons', [
            'name'    => OtpTestData::ICON_JPEG,
            'content' => OtpTestData::ICON_JPEG_DATA,
        ]);
    }
}
