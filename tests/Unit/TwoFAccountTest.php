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
     * test Totp TwoFAccount display via API
     *
     * @test
     */
    public function testTotpTwofaccountDisplay()
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
                'imageLink' => null,
            ])
            ->assertJsonMissing([
                'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'algorithm' => 'sha1',
            ]);
    }


    /**
     * test Hotp TwoFAccount display via API
     *
     * @test
     */
    public function testHotpTwofaccountDisplayWithCounterIncrement()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testTOTP',
            'account' => 'test@test.com',
            'uri' => 'otpauth://hotp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test&counter=1',
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/token', ['id' => $twofaccount->id])
            ->assertStatus(200);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/' . $twofaccount->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'testTOTP',
                'account' => 'test@test.com',
                'group_id' => null,
                'isConsistent' => true,
                'otpType' => 'hotp',
                'digits' => 6,
                'hotpCounter' => 2,
                'imageLink' => null,
            ])
            ->assertJsonMissing([
                'uri' => 'otpauth://hotp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'algorithm' => 'sha1',
            ]);
    }


    /**
     * test TwoFAccount display via API
     *
     * @test
     */
    public function testTwofaccountDisplayWithSensitive()
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
    public function testMissingTwofaccountDisplay()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/1000')
            ->assertStatus(404);
    }


    /**
     * test TwoFAccount preview via API
     *
     * @test
     */
    public function testTwofaccountPreview()
    {
        Storage::put('test.png', 'emptied to prevent missing resource replaced by null by the model getter');

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/preview', [
                'uri' => 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&issuer=service&image=https%3A%2F%2Fen.opensuse.org%2Fimages%2F4%2F44%2FButton-filled-colour.png',
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'service' => 'service',
                'account' => 'account',
                'uri' => 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&issuer=service&image=https%3A%2F%2Fen.opensuse.org%2Fimages%2F4%2F44%2FButton-filled-colour.png',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'algorithm' => 'sha1',
                'otpType' => 'totp',
                'digits' => 6,
                'totpPeriod' => 30,
                'hotpCounter' => null,
                'imageLink' => 'https://en.opensuse.org/images/4/44/Button-filled-colour.png',
            ])
            ->assertJsonStructure([
                'icon'
            ]);
    }


    /**
     * test TwoFAccount preview with unreachable image parameter via API
     *
     * @test
     */
    public function testTwofaccountPreviewWithUnreachableImage()
    {

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/preview', [
                'uri' => 'otpauth://totp/service:account?secret=A4GRFHVVRBGY7UIW&issuer=service&image=https%3A%2F%2Fen.opensuse.org%2Fimage.png',
            ])
            ->assertStatus(200)
            ->assertJsonMissing([
                'icon'
            ]);
    }


    /**
     * test TwoFAccount creation via API
     *
     * @test
     */
    public function testTwofaccountCreationSubmittedByQuickForm()
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
     * test Twofaccount Creation Submitted By Advanced Form Without Otp Option via API
     *
     * @test
     */
    public function testTwofaccountCreationSubmittedByAdvancedFormWithoutOtpOption()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'icon' => 'test.png',
                    'secret' => 'A4GRFHVVRBGY7UIW',
                    'secretIsBase32Encoded' => 1,
                    'otpType' => 'totp',
                    'algorithm' => null,
                    'digits' => null,
                    'totpPeriod' => null,
                    'hotpCounter' => null,
                    'imageLink' => null,
                ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'service' => 'testCreation',
                'account' => 'test@example.org',
                'icon' => 'test.png',
                'digits' => 6,
                'totpPeriod' => 30,
                'hotpCounter' => null,
                'imageLink' => null,
            ])
            ->assertJsonMissing([
                'uri' => 'otpauth://totp/test@test.com?secret=A4GRFHVVRBGY7UIW&issuer=test',
                'algorithm' => null,
                'secret' => 'A4GRFHVVRBGY7UIW',
            ]);
    }


    /**
     * test Twofaccount Creation Submitted By Advanced Form with Otp Option via API
     *
     * @test
     */
    public function testTwofaccountCreationSubmittedByAdvancedFormWithOtpOption()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts', [
                    'service' => 'testCreation',
                    'account' => 'test@example.org',
                    'icon' => 'test.png',
                    'secret' => 'A4GRFHVVRBGY7UIW',
                    'secretIsBase32Encoded' => 1,
                    'otpType' => 'totp',
                    'algorithm' => 'sha256',
                    'digits' => 8,
                    'totpPeriod' => 40,
                    'hotpCounter' => null,
                ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'service' => 'testCreation',
                'account' => 'test@example.org',
                'icon' => 'test.png',
                'digits' => 8,
                'totpPeriod' => 40,
                'hotpCounter' => null,
            ])
            ->assertJsonMissing([
                'uri' => '',
                'algorithm' => null,
                'secret' => 'A4GRFHVVRBGY7UIW',
            ]);
    }


    /**
     * test TwoFAccount creation when fiels are empty via API
     *
     * @test
     */
    public function testTwofaccountCreationWithEmptyRequest()
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
    public function testTwofaccountCreationWithInvalidTotp()
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
            ->assertStatus(200)
            ->assertJsonFragment([
                'isConsistent' => false,
            ]);
    }


    /**
     * test totp  token generation for a given existing account via API
     *
     * @test
     */
    public function testTotpTokenGenerationWithAccountId()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testService',
            'account' => 'testAccount',
            'uri' => 'otpauth://totp/testService:testAccount?secret=A4GRFHVVRBGY7UIW&issuer=testService'
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/token', ['id' => $twofaccount->id])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'totpTimestamp',
                'totpPeriod',
            ]);
    }


    /**
     * test hotp token generation for a given existing account via API
     *
     * @test
     */
    public function testHotpTokenGenerationWithAccountId()
    {
        $twofaccount = factory(TwoFAccount::class)->create([
            'service' => 'testService',
            'account' => 'testAccount',
            'uri' => 'otpauth://hotp/testService:testAccount?secret=A4GRFHVVRBGY7UIW&issuer=testService&counter=1'
        ]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/token', ['id' => $twofaccount->id])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
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
            ->json('POST', '/api/twofaccounts/token', ['otp' => ['uri' => $uri]])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'totpTimestamp',
                'totpPeriod',
            ]);
    }


    /**
     * test totp token generation by providing an array of otp attributes without URI via API
     *
     * @test
     */
    public function testTotpTokenGenerationWithAttributesArray()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/token', ['otp' => [
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
            ->assertJsonStructure([
                'token',
                'totpTimestamp',
                'totpPeriod',
            ]);
    }


    /**
     * test hotp token generation by providing an array of otp attributes without URI via API
     *
     * @test
     */
    public function testHotpTokenGenerationWithAttributesArray()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/token', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'hotp',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'secretIsBase32Encoded' => 1,
                'digits' => 6,
                'hotpCounter' => 1,
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'hotpCounter',
            ]);
    }


    /**
     * test token generation by providing an array of otp attributes with a bad otp type via API
     *
     * @test
     */
    public function testTokenGenerationWithBadOtptypeAttribute()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/token', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'otp',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'secretIsBase32Encoded' => 1,
                'digits' => 6,
                'totpPeriod' => 30,
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'otpType'
                ]
            ]);
    }


    /**
     * test token generation by providing an array of otp attributes without secret via API
     *
     * @test
     */
    public function testTokenGenerationWithMissingSecretAttribute()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/token', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'totp',
                'secret' => 'A4GRFHVVRBGY7UIW',
                'secretIsBase32Encoded' => 1,
                'digits' => 'x',
                'totpPeriod' => 'y',
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'qrcode'
                ]
            ]);
    }


    /**
     * test token generation by providing an array of bad attributes  via API
     *
     * @test
     */
    public function testTokenGenerationWithBadAttribute()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/twofaccounts/token', ['otp' => [
                'service' => 'service',
                'account' => 'account',
                'otpType' => 'totp',
                'secret' => '',
                'secretIsBase32Encoded' => 1,
                'digits' => 6,
                'totpPeriod' => 30,
                'algorithm' => 'sha1',
                'uri' => ''
            ]])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'secret'
                ]
            ]);
    }


    /**
     * test TwoFAccount TOTP update via API
     *
     * @test
     */
    public function testTwofaccountTotpUpdate()
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
                    'uri' => ''
                ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1,
                'service' => 'service',
                'account' => 'account',
                'icon' => 'testUpdate.png',
                'otpType' => 'totp',
                'digits' => 8,
                'totpPeriod' => 40
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
    public function testTwofaccountHotpUpdate()
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
                    'uri' => ''
                ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1,
                'service' => 'testUpdate.com',
                'account' => 'testUpdate',
                'icon' => 'testUpdate.png',
                'otpType' => 'hotp',
                'digits' => 8,
                'hotpCounter' => 5
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
    public function testTwofaccountUpdateOfMissingTwoFAccount()
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
    public function testTwofaccountIndexListing()
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
                    'isConsistent',
                    'order_column',
                    'group_id',
                    'otpType'
                ]
            ]
        );
    }


    /**
     * test TwoFAccount index fetching via API
     *
     * @test
     */
    public function testTwofaccountCount()
    {
        $twofaccount = factory(TwoFAccount::class, 3)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/twofaccounts/count')
            ->assertStatus(200)
            ->assertJson([
                'count' => 3
            ]
        );
    }


    /**
     * test TwoFAccount deletion via API
     *
     * @test
     */
    public function testTwofaccountDeletion()
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
    public function testTwofaccountBatchDestroy()
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
    public function testTwofaccountReorder()
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
    public function testShowQrcode()
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
