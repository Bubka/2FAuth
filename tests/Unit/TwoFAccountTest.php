<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\TwoFAccount;
use Illuminate\Support\Facades\Storage;

class TwoFAccountTest extends TestCase
{
    /** @var \App\User */
    protected $user;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }


    /**
     * test TwoFAccount display via API
     *
     * @test
     */
    public function testTwoFAccountDisplay()
    {
        Storage::put('test.png', 'emptied to prevent missing resource replaced by null by the model getter');

        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testTOTP',
            'account' => 'test@test.com',
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
            'icon' => 'test.png',
        ]);


        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $twofaccount->id)
            ->assertStatus(200)
            ->assertJson([
                'service' => 'testTOTP',
                'account' => 'test@test.com',
                'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
                'icon' => 'test.png',
            ]);
    }


    /**
     * test missing TwoFAccount display via API
     *
     * @test
     */
    public function testMissingTwoFAccountDisplay()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/1000')
            ->assertStatus(404);
    }


    /**
     * test TwoFAccount creation via API
     *
     * @test
     */
    public function testTwoFAccountCreation()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test',
                    'icon' => 'test.png',
                ])
            ->assertStatus(201)
            ->assertJson([
                'service' => 'testCreation',
                'account' => 'test@example.org',
                'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test',
                'icon' => 'test.png',
            ]);
    }


    /**
     * test TwoFAccount creation when fiels are empty via API
     *
     * @test
     */
    public function testTwoFAccountCreationWithEmptyRequest()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => '',
                    'account' => '',
                    'uri' => '',
                    'icon' => '',
                ])
            ->assertStatus(422);
    }


    /**
     * test TwoFAccount creation with an invalid TOTP uri via API
     *
     * @test
     */
    public function testTwoFAccountCreationWithInvalidTOTP()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'uri' => 'invalidTOTP',
                    'icon' => 'test.png',
                ])
            ->assertStatus(422);
    }


    /**
     * test TOTP generation via API
     *
     * @test
     */
    public function testTOTPgeneration()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testTOTP',
            'account' => 'test@test.com',
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test'
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/otp', ['data' => $twofaccount->id])
            ->assertStatus(200)
            ->assertJsonStructure([
                'otp',
            ]);
    }


    /**
     * test TwoFAccount update via API
     *
     * @test
     */
    public function testTwoFAccountUpdate()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/twofaccounts/' . $twofaccount->id, [
                    'service' => 'testUpdate',
                    'account' => 'testUpdate@test.com',
                    'icon' => 'testUpdate.png',
                ])
            ->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'service' => 'testUpdate',
                'account' => 'testUpdate@test.com',
                'uri' => $twofaccount->uri,
                'icon' => 'testUpdate.png',
            ]);
    }


    /**
     * test TwoFAccount update via API
     *
     * @test
     */
    public function testTwoFAccountUpdateOfMissingTwoFAccount()
    {
        $twofaccount = factory(TwoFAccount::class)->create();
        $id = $twofaccount->id;
        $twofaccount->delete();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/twofaccounts/' . $id, [
                    'service' => 'testUpdate'
                ])
            ->assertStatus(404);
    }


    /**
     * test TwoFAccount index fetching via API
     *
     * @test
     */
    public function testTwoFAccountIndexListing()
    {
        $twofaccount = factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts')
            ->assertStatus(200)
            ->assertJsonCount(3, $key = null)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'service',
                    'account',
                    'uri',
                    'icon',
                    'created_at',
                    'updated_at'
                ]
            ]
        );
    }


    /**
     * test TwoFAccount deletion via API
     *
     * @test
     */
    public function testTwoFAccountDeletion()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/twofaccounts/' . $twofaccount->id)
            ->assertStatus(204);
    }

}
