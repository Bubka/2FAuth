<?php

namespace Tests\Api\v1\Controllers;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\FeatureTestCase;

/**
 * @covers \App\Api\v1\Controllers\IconController
 */
class IconControllerTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function test_upload_icon_returns_filename()
    {
        $file = UploadedFile::fake()->image('testIcon.jpg');

        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons', [
                'icon' => $file,
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'filename',
            ]);
    }

    /**
     * @test
     */
    public function test_upload_with_invalid_data_returns_validation_error()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons', [
                'icon' => null,
            ])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_fetch_logo_returns_filename()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service' => 'twitter',
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'filename',
            ]);
    }

    /**
     * @test
     */
    public function test_fetch_unknown_logo_returns_nothing()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('POST', '/api/v1/icons/default', [
                'service' => 'unknown_company',
            ])
            ->assertNoContent();
    }

    /**
     * @test
     */
    public function test_delete_icon_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/icons/testIcon.jpg')
            ->assertNoContent(204);
    }

    /**
     * @test
     */
    public function test_delete_invalid_icon_returns_success()
    {
        $response = $this->actingAs($this->user, 'api-guard')
            ->json('DELETE', '/api/v1/icons/null')
            ->assertNoContent(204);
    }

    /**
     * @test
     */
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
