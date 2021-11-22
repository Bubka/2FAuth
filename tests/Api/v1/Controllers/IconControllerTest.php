<?php

namespace Tests\Api\v1\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


/**
 * @covers \App\Api\v1\Controllers\IconController
 */
class IconControllerTest extends TestCase
{

    use WithoutMiddleware;


    /**
     * @test
     */
    public function test_upload_icon_returns_filename()
    {
        $file = UploadedFile::fake()->image('testIcon.jpg');

        $response = $this->json('POST', '/api/v1/icons', [
                'icon' => $file,
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'filename'
            ]);
    }


    /**
     * @test
     */
    public function test_upload_with_invalid_data_returns_validation_error()
    {
        $response = $this->json('POST', '/api/v1/icons', [
                'icon' => null,
            ])
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_delete_icon_returns_success()
    {
        $response = $this->json('DELETE', '/api/v1/icons/testIcon.jpg')
            ->assertNoContent(204);

    }


    /**
     * @test
     */
    public function test_delete_invalid_icon_returns_success()
    {
        $response = $this->json('DELETE', '/api/v1/icons/null')
            ->assertNoContent(204);

    }

}