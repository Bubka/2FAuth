<?php

namespace Tests\Api\v1\Controllers;

use App\Api\v1\Controllers\IconController;
use App\Facades\IconStore;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Services\LogoService;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
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

    public function setUp() : void
    {
        parent::setUp();

        Storage::fake('icons');
        Storage::fake('logos');

        Http::preventStrayRequests();
        Http::fake([
            LogoService::TFA_IMG_URL . '*' => Http::response(HttpRequestTestData::SVG_LOGO_BODY, 200),
            LogoService::TFA_URL           => Http::response(HttpRequestTestData::TFA_JSON_BODY, 200),
        ]);
        Http::fake([
            OtpTestData::EXTERNAL_IMAGE_URL_DECODED => Http::response((new FileFactory)->image('file.png', 10, 10)->tempFile, 200),
        ]);

        $this->user = User::factory()->create();
    }

    #[Test]
    public function test_upload_icon_returns_filename_using_the_iconStore()
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
    public function test_fetch_logo_returns_filename()
    {
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
    public function test_fetch_unknown_logo_returns_nothing()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service' => 'unknown_company',
            ])
            ->assertNoContent();
    }

    #[Test]
    public function test_delete_icon_returns_success_using_the_iconStore()
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
