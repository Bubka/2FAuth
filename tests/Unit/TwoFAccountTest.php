<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\TwoFAccount;

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
                    'uri' => 'test',
                ])
            ->assertStatus(201)
            ->assertJson([
                'service' => 'testCreation',
                'account' => 'test@example.org',
                'uri' => 'test',
            ]);
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
            ->json('GET', '/api/twofaccounts/' . $twofaccount->id . '/totp')
            ->assertStatus(200)
            ->assertJsonStructure([
                'totp',
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
                    'uri' => 'testUpdate',
                ])
            ->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'service' => 'testUpdate',
                'account' => 'testUpdate@test.com',
                'uri' => 'testUpdate',
                'icon' => null,
            ]);
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
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'service',
                    'account',
                    'uri',
                    'icon',
                    'created_at',
                    'updated_at',
                    'deleted_at'
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


    /**
     * test TwoFAccount permanent deletion via API
     *
     * @test
     */
    public function testTwoFAccountPermanentDeletion()
    {
        $twofaccount = factory(TwoFAccount::class)->create();
        $twofaccount->delete();

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/twofaccounts/force/' . $twofaccount->id)
            ->assertStatus(204);
    }

}
