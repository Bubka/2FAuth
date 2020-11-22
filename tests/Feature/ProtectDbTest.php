<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\TwoFAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ProtectDbTest extends TestCase
{
    /** @var \App\User, \App\TwoFAccount */
    protected $user, $twofaccounts;


    /**
     * @test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->twofaccount = factory(Twofaccount::class,)->create([
            'service' => 'test',
            'account' => 'test@test.com',
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
        ]);
        $this->twofaccountAlt = factory(Twofaccount::class,)->create([
            'service' => 'testAlt',
            'account' => 'testAlt@test.com',
            'uri' => 'otpauth://totp/testAlt@test.com?secret=A4GRFHVVRBGY7UIW&issuer=testAlt',
        ]);
    }


    /**
     * test db encryption via API
     *
     * @test
     */
    public function testDbEncryption()
    {
        // Encrypt db
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => true,
                ])
            ->assertStatus(200);

        // Get the raw encrypted records
        $encrypted = DB::table('twofaccounts')->find($this->twofaccount->id);
        $encryptedAlt = DB::table('twofaccounts')->find($this->twofaccountAlt->id);

        // Get the accounts via API and check their consistency with raw data
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $this->twofaccount->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'test',
                'account' => Crypt::decryptString($encrypted->account),
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $this->twofaccountAlt->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'testAlt',
                'account' => Crypt::decryptString($encryptedAlt->account),
            ]);
    }


    /**
     * test Account update on protected DB via API
     *
     * @test
     */
    public function testTwofaccountUpdateOnProtectedDb()
    {
        // Encrypt db
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => true,
                ])
            ->assertStatus(200);

        // Only the Account field is encrypted
        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/twofaccounts/' . $this->twofaccount->id, [
                    'service' => 'testUpdate',
                    'account' => 'testUpdate@test.com',
                    'otpType' => 'totp',
                    'secret' => 'A4GRFHVVRBGY7UIW',
                    'secretIsBase32Encoded' => 1,
                    'digits' => 8,
                    'totpPeriod' => 30,
                    'algorithm' => 'sha256',
                ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1,
                'service' => 'testUpdate',
                'account' => 'testUpdate@test.com',
            ]);
    }


    /**
     * test db encryption via API
     *
     * @test
     */
    public function testPreventDbEncryptionOnDbAlreadyEncrypted()
    {
        // Encrypt db
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => true,
                ]);

        // Set the option again to force another encryption pass
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => true,
                ]);

        // Get the account, it should be readable
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $this->twofaccount->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'test',
                'account' => 'test@test.com',
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $this->twofaccountAlt->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'testAlt',
                'account' => 'testAlt@test.com',
            ]);
    }


    /**
     * test db deciphering via API
     *
     * @test
     */
    public function testDbDeciphering()
    {
        // Encrypt db
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => true,
                ]);

        // Decipher db
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => false,
                ])
            ->assertStatus(200);

        // Get the accounts, they should be readable
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $this->twofaccount->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'test',
                'account' => 'test@test.com',
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $this->twofaccountAlt->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'testAlt',
                'account' => 'testAlt@test.com',
            ]);
    }


    /**
     * test Protect DB option not being persisted if encryption fails via API
     *
     * @test
     */
    public function testAbortEncryptionIfSomethingGoesWrong()
    {
        // Set no APP_KEY to break Laravel encryption capability
        config(['app.key' => '']);

        // Decipher db
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => true,
                ])
            ->assertStatus(400);

        // Check ProtectDB option is not active
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/settings/options')
            ->assertJsonFragment([
                'useEncryption' => false
            ]);
    }


    /**
     * test Protect DB option not being persisted if decyphering fails via API
     *
     * @test
     */
    public function testAbortDecipheringIfSomethingGoesWrong()
    {
        // Encrypt db
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => true,
                ])
            ->assertStatus(200);

        // alter the ciphertext to make deciphering impossible
        $affected = DB::table('twofaccounts')
              ->where('id', 1)
              ->update(['account' => 'xxxxxxxxx', 'uri' => 'yyyyyyyyy']);

        // Decipher db
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => false,
                ])
            ->assertStatus(400);

        // Check ProtectDB option has been restored
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/settings/options')
            ->assertJsonFragment([
                'useEncryption' => true
            ]);
    }


    /**
     * test bad payload don't breaks anything via API
     *
     * @test
     */
    public function testBadPayloadDontBreakEncryptedAccountFetching()
    {
        // Encrypt db
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'useEncryption' => true,
                ])
            ->assertStatus(200);

        // break the payload
        DB::table('twofaccounts')
            ->where('id', 1)
            ->update([
                'account' => 'YouShallNotPass',
                'uri' => 'PasDeBrasPasDeChocolat',
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/1')
            ->assertStatus(200)
            ->assertJsonFragment([
                'account' => '*encrypted*',
                'service' => 'test',
                'group_id' => null,
                'isConsistent' => false,
                'otpType' => null,
                'digits' => null,
                'hotpCounter' => null,
                'totpPeriod' => null,
            ])
            ->assertJsonMissing([
                'uri' => '*encrypted*',
            ]);
    }

}