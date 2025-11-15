<?php

namespace Tests\Api\v1\Controllers;

use App\Api\v1\Controllers\IconController;
use App\Facades\IconStore;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Classes\LocalFile;
use Tests\Data\CommonDataProvider;
use Tests\Data\HttpRequestTestData;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * IconController test class
 */
#[CoversClass(IconController::class)]
class IconControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected function setUp() : void
    {
        parent::setUp();

        Storage::fake('icons');
        Storage::fake('iconPacks');
        Storage::fake('logos');

        Http::preventStrayRequests();
        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => Http::response((new FileFactory)->image('file.png', 10, 10)->tempFile, 200),
        ]);

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_upload_icon_returns_filename_using_the_icon_store()
    {
        $iconName = 'testIcon.jpg';
        $file     = UploadedFile::fake()->image($iconName);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons', [
                'icon' => $file,
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'filename',
            ]);
    }

    #[Test]
    public function test_upload_icon_stores_it_to_database()
    {
        $file = UploadedFile::fake()->image('testIcon.jpg');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons', [
                'icon' => $file,
            ]);
    }

    #[Test]
    public function test_upload_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons', [
                'icon' => null,
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_upload_infected_svg_data_stores_stores_sanitized_svg_content()
    {
        $file = LocalFile::fake()->infectedSvgIconFile();

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons', [
                'icon' => $file,
            ])
            ->assertCreated();

        $svgContent = IconStore::get($response->getData()->filename);
        $this->assertStringNotContainsString(OtpTestData::ICON_SVG_MALICIOUS_CODE, $svgContent);
    }

    #[Test]
    public function test_fetch_logo_returns_filename()
    {
        Http::fake([
            CommonDataProvider::SELFH_URL => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service' => 'service',
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'filename',
            ]);
    }

    #[Test]
    public function test_fetch_logo_using_specified_iconcollection_returns_filename()
    {
        Http::fake([
            CommonDataProvider::SELFH_URL          => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            CommonDataProvider::DASHBOARDICONS_URL => Http::response(OtpTestData::ICON_SVG_DATA, 200),
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service'        => 'service',
                'iconCollection' => 'dashboardicons',
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'filename',
            ]);

        // Don't know why but 'getData()->filename' has some unwanted spaces in it so we trim them
        $svgContent = trim(str_replace('> <', '><', IconStore::get($response->getData()->filename)));

        $this->assertEquals(OtpTestData::ICON_SVG_DATA, $svgContent);
    }

    #[Test]
    public function test_fetch_returns_a_logo_from_the_requested_iconpack()
    {
        $requestedIconPack = 'packDir';

        Storage::disk('iconPacks')->put($requestedIconPack . '/' . OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA);
        Storage::disk('iconPacks')->put('anotherPackDir/' . OtpTestData::ICON_PNG, base64_decode(OtpTestData::ICON_PNG_DATA));

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service'  => OtpTestData::ICON_NAME,
                'iconPack' => $requestedIconPack,
            ]);

        $this->assertStringEndsWith('.svg', $response->getData()->filename);
    }

    #[Test]
    public function test_fetch_logo_return_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service'        => 'service',
                'iconCollection' => 'not_a_valid_icon_collection',
            ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_fetch_logo_from_iconcollection_with_infected_svg_data_stores_sanitized_svg_content()
    {
        Http::fake([
            CommonDataProvider::SELFH_URL => Http::response(OtpTestData::ICON_SVG_DATA_INFECTED, 200),
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service' => 'service',
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'filename',
            ]);

        $svgContent = IconStore::get($response->getData()->filename);
        $this->assertStringNotContainsString(OtpTestData::ICON_SVG_MALICIOUS_CODE, $svgContent);
    }

    #[Test]
    public function test_fetch_logo_from_iconpack_with_infected_svg_data_stores_sanitized_svg_content()
    {
        Storage::disk('iconPacks')->put('packDir/' . OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA_INFECTED);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service'  => OtpTestData::ICON_NAME,
                'iconPack' => 'packDir',
            ])
            ->assertStatus(201);

        $svgContent = IconStore::get($response->getData()->filename);
        $this->assertStringNotContainsString(OtpTestData::ICON_SVG_MALICIOUS_CODE, $svgContent);
    }

    #[Test]
    public function test_fetch_unknown_logo_in_iconcollection_returns_nothing()
    {
        Http::fake([
            CommonDataProvider::SELFH_URL => Http::response('not found', 404),
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service' => 'NameOfAnUnknownServiceForSure',
            ])
            ->assertNoContent();
    }

    #[Test]
    public function test_fetch_unknown_logo_in_iconpack_returns_nothing()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service'  => 'NameOfAnUnknownServiceForSure',
                'iconPack' => 'packDir',
            ])
            ->assertNoContent();
    }

    #[Test]
    public function test_icon_packs_returns_available_icon_packs_sorted()
    {
        Storage::disk('iconPacks')->put('packDirWithSvg/' . OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA);
        Storage::disk('iconPacks')->put('anotherPackDirWithSvg/' . OtpTestData::ICON_SVG, OtpTestData::ICON_SVG_DATA);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/icons/packs')
            ->assertStatus(200)
            ->assertExactJson([
                '0' => [
                    'name' => 'anotherPackDirWithSvg',
                ],
                '1' => [
                    'name' => 'packDirWithSvg',
                ],
            ]);
    }

    #[Test]
    public function test_icon_packs_returns_empty_collection()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('GET', '/api/v1/icons/packs')
            ->assertStatus(200)
            ->assertExactJson([]);
    }

    #[Test]
    public function test_delete_icon_returns_success_using_the_icon_store()
    {
        IconStore::spy();

        $iconName = 'testIcon.jpg';

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/icons/' . $iconName)
            ->assertNoContent(204);

        IconStore::shouldHaveReceived('delete')->once()->with($iconName);
    }

    #[Test]
    public function test_delete_invalid_icon_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/icons/null')
            ->assertNoContent(204);
    }

    #[Test]
    public function test_delete_icon_of_another_user_is_forbidden()
    {
        $anotherUser = User::factory()->create();

        TwoFAccount::factory()->for($anotherUser)->create([
            'icon' => 'testIcon.jpg',
        ]);

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/icons/testIcon.jpg')
            ->assertForbidden()
            ->assertJsonStructure([
                'message',
            ]);
    }
}
