<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\TwoFAccount;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Auth\Authenticatable;

class TwoFAccountTest extends TestCase
{

    /**
     * Rollback and execute migrations for each test.
     */
    use DatabaseTransactions;


    /**
     * set up fresh db
     */
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate', ['--seed' => true]);
        Artisan::call('passport:install',['--verbose' => 2]);
    }


    /**
     * test TwoFAccount creation via API
     *
     * @return void
     */
    public function testTwoFAccountCreation()
    {
        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'name' => 'testCreation',
                    'uri' => 'test',
                ])
            ->assertStatus(201)
            ->assertJson([
                'name' => 'testCreation',
                'uri' => 'test',
            ]);
    }


    /**
     * test TOTP generation via API
     *
     * @return void
     */
    public function testTOTPgeneration()
    {
        $user = \App\User::find(1);

        $twofaccount = TwoFAccount::create([
            'name' => 'testTOTP',
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test'
        ]);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts/' . $twofaccount->id . '/totp')
            ->assertStatus(200)
            ->assertJsonStructure([
                'totp',
            ]);
    }


    /**
     * test TwoFAccount update via API
     *
     * @return void
     */
    public function testTwoFAccountUpdate()
    {
        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('PUT', '/api/twofaccounts/1', [
                    'name' => 'testUpdate',
                    'uri' => 'testUpdate',
                ])
            ->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'name' => 'testUpdate',
                'uri' => 'testUpdate',
                'icon' => null,
            ]);
    }


    /**
     * test TwoFAccount index fetching via API
     *
     * @return void
     */
    public function testTwoFAccountIndexListing()
    {
        $user = \App\User::find(1);

        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/twofaccounts')
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
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
     * @return [type] [description]
     */
    public function testTwoFAccountDeletion()
    {
        $user = \App\User::find(1);

        $twofaccount = TwoFAccount::create([
            'name' => 'testDelete',
            'uri' => 'test'
        ]);

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', '/api/twofaccounts/' . $twofaccount->id)
            ->assertStatus(204);
    }


    /**
     * test TwoFAccount permanent deletion via API
     * @return [type] [description]
     */
    public function testTwoFAccountPermanentDeletion()
    {
        $user = \App\User::find(1);

        $twofaccount = TwoFAccount::create([
            'name' => 'testHardDelete',
            'uri' => 'test'
        ]);

        $twofaccount->delete();

        $response = $this->actingAs($user, 'api')
            ->json('DELETE', '/api/twofaccounts/force/' . $twofaccount->id)
            ->assertStatus(204);
    }

}
