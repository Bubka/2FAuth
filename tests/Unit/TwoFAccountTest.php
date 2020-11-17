<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\TwoFAccount;
use Illuminate\Support\Facades\DB;
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
            'icon' => 'test.png'
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $twofaccount->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'testTOTP',
                'account' => 'test@test.com',
                'icon' => 'test.png',
                'group_id' => null,
                'isConsistent' => true,
                'otpType' => 'totp',
                'digits' => 6,
                'totpPeriod' => 30,
                'hotpCounter' => null,
                'imageLink' => null,
            ])
            ->assertJsonMissing([
                'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'algorithm' => 'sha1',
            ]);
    }


    /**
     * test TwoFAccount display via API
     *
     * @test
     */
    public function testTwoFAccountDisplayWithSensitive()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testTOTP',
            'account' => 'test@test.com',
            'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW',
        ]);


        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $twofaccount->id . '/withSensitive')
            ->assertStatus(200)
            ->assertJsonFragment([
                'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'algorithm' => 'sha1',
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
            ->assertJsonFragment([
                'service' => 'testCreation',
                'account' => 'test@example.org',
                'icon' => 'test.png',
            ])
            ->assertJsonMissing([
                'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
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
     * test show account when uri field remains encrypted via API
     *
     * @test
     */
    public function testShowAccountWithUndecipheredUri()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHZVRBGY7UIW&issuer=test',
                    'icon' => 'test.png',
                ])
            ->assertStatus(201);

        DB::table('twofaccounts')
            ->where('id', 1)
            ->update([
                'uri' => '**encrypted**',
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/1')
            ->assertStatus(422);
    }


    /**
     * test token generation for a given existing account via API
     *
     * @test
     */
    public function testTokenGenerationWithAccountId()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testService',
            'account' => 'testAccount',
            'uri' => 'otpauth://totp/testService:testAccount?secret=A4GRFHVVRBGY7UIW&issuer=testService'
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/otp', ['id' => $twofaccount->id])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'totpTimestamp'
            ]);
    }


    /**
     * test token generation by providing an URI via API
     *
     * @test
     */
    public function testTokenGenerationWithUri()
    {
        $uri = 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&issuer=service';

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/otp', ['otp' => ['uri' => $uri]])
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'service',
                'account' => 'account',
            ])
            ->assertJsonStructure([
                'token',
                'totpTimestamp'
            ]);
    }


    /**
     * test token generation by providing an array of otp attributes without URI via API
     *
     * @test
     */
    public function testTokenGenerationWithAttributesArray()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/otp', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'totp',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'secretIsBase32Encoded' => 1,
                'digits' => 6,
                'totpPeriod' => 30,
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'service',
                'account' => 'account',
            ])
            ->assertJsonStructure([
                'token',
                'totpTimestamp'
            ]);
    }


    /**
     * test TwoFAccount TOTP update via API
     *
     * @test
     */
    public function testTwoFAccountTOTPUpdate()
    {
        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/twofaccounts/' . $twofaccount->id, [
                    'service' => 'service',
                    'account' => 'account',
                    'icon' => 'testUpdate.png',
                    'otpType' => 'totp',
                    'secret' => 'A4GRFHVVRBGY7UIW',
                    'secretIsBase32Encoded' => 1,
                    'digits' => 8,
                    'totpPeriod' => 40,
                    'algorithm' => 'sha256',
                    'uri' => '',
                    'imageLink' => 'http://www.image.net/file.png'
                ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1,
                'service' => 'service',
                'account' => 'account',
                'icon' => 'testUpdate.png',
                'otpType' => 'totp',
                'digits' => 8,
                'totpPeriod' => 40,
                'imageLink' => 'http://www.image.net/file.png'
            ])
            ->assertJsonMissing([
                'uri' => $twofaccount->uri,
                'secret' => 'A4GRFHVVRBGY7UIW',
                'algorithm' => 'sha256',
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $twofaccount->id . '/withSensitive')
            ->assertStatus(200)
            ->assertJsonFragment([
                'secret' => 'A4GRFHVVRBGY7UIW',
                'algorithm' => 'sha256',
            ])
            ->assertJsonStructure([
                'uri',
            ]);
    }


    /**
     * test TwoFAccount HOTP update via API
     *
     * @test
     */
    public function testTwoFAccountHOTPUpdate()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'service',
            'account' => 'account',
            'uri' => 'otpauth://hotp/service:account?counter=1&secret=A4GRFHVVRBGY7UIW'
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/twofaccounts/' . $twofaccount->id, [
                    'service' => 'testUpdate.com',
                    'account' => 'testUpdate',
                    'icon' => 'testUpdate.png',
                    'otpType' => 'hotp',
                    'secret' => 'BBBBFFFFEEEEAAAA',
                    'secretIsBase32Encoded' => 1,
                    'digits' => 8,
                    'hotpCounter' => 5,
                    'algorithm' => 'sha256',
                    'uri' => '',
                    'imageLink' => 'http://www.image.net/file.png'
                ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1,
                'service' => 'testUpdate.com',
                'account' => 'testUpdate',
                'icon' => 'testUpdate.png',
                'otpType' => 'hotp',
                'digits' => 8,
                'hotpCounter' => 5,
                'imageLink' => 'http://www.image.net/file.png'
            ])
            ->assertJsonMissing([
                'uri' => $twofaccount->uri,
                'secret' => 'BBBBFFFFEEEEAAAA',
                'algorithm' => 'sha256',
            ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $twofaccount->id . '/withSensitive')
            ->assertStatus(200)
            ->assertJsonFragment([
                'secret' => 'BBBBFFFFEEEEAAAA',
                'algorithm' => 'sha256',
            ])
            ->assertJsonStructure([
                'uri',
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
                    'service' => 'testUpdate.com',
                    'account' => 'testUpdate',
                    'icon' => 'testUpdate.png',
                    'otpType' => 'hotp',
                    'secret' => 'BBBBFFFFEEEEAAAA',
                    'secretIsBase32Encoded' => 1,
                    'digits' => 8,
                    'hotpCounter' => 5,
                    'algorithm' => 'sha256',
                    'uri' => '',
                    'imageLink' => 'http://www.image.net/file.png'
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
                    'icon',
                    'created_at',
                    'updated_at',
                    'isConsistent'
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
     * test TwoFAccounts batch deletion via API
     *
     * @test
     */
    public function testTwoFAccountBatchDestroy()
    {
        factory(TwoFAccount::class, 3)->create();

        $ids = \Illuminate\Support\Facades\DB::table('twofaccounts')->value('id');

        $response = $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/twofaccounts/batch', [
                'data' => $ids])
            ->assertStatus(204);
    }


    /**
     * test TwoFAccounts reorder
     *
     * @test
     */
    public function testTwoFAccountReorder()
    {
        factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/twofaccounts/reorder', [
                'orderedIds' => [3,2,1]])
            ->assertStatus(200);
    }


    /**
     * test show QR code via API
     *
     * @test
     */
    public function testShowQRCode()
    {

        $twofaccount = factory(TwoFAccount::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/qrcode/' . $twofaccount->id)
            ->assertJsonStructure([
                'qrcode',
            ])
            ->assertStatus(200);
            
            $this->assertStringStartsWith('data:image/png;base64', $response->getData()->qrcode);
    }

}
