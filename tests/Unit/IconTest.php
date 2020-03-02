<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class IconTest extends TestCase
{

    use WithoutMiddleware;


    /**
     * test upload icon with no missing image resource via API
     *
     * @test
     */
    public function testIconUploadWithMissingImage()
    {

        $response = $this->json('POST', '/api/icon/upload', [
                    'icon' => '',
                ])
            ->assertStatus(422);
    }


    /**
     * test upload icon via API
     *
     * @test
     */
    public function testIconUpload()
    {

        $file = UploadedFile::fake()->image('testIcon.jpg');

        $response = $this->json('POST', '/api/icon/upload', [
                    'icon' => $file,
                ])
            ->assertStatus(201);

    }


    /**
     * test delete an uploaded icon via API
     *
     * @test
     */
    public function testIconDelete()
    {

        $response = $this->json('DELETE', '/api/icon/delete/testIcon.jpg')
            ->assertStatus(204);

    }

}