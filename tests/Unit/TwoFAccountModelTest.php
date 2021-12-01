<?php

namespace Tests\Unit;

use App\TwoFAccount;
use App\Events\TwoFAccountDeleted;
use Tests\ModelTestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

/**
 * @covers \App\TwoFAccount
 */
class TwoFAccountModelTest extends ModelTestCase
{

    /**
     * @test
     */
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(
            new TwoFAccount(),
            [],
            [],
            ['*'],
            [],
            ['id' => 'int'],
            ['deleted' => TwoFAccountDeleted::class],
            ['created_at', 'updated_at'],
            \Illuminate\Database\Eloquent\Collection::class,
            'twofaccounts',
            'id',
            true
        );
    }


    /**
     * @test
     * 
     * @dataProvider provideSensitiveAttributes
     */
    public function test_sensitive_attributes_are_stored_encrypted(string $attribute)
    {
        \Facades\App\Services\SettingService::shouldReceive('get')
            ->with('useEncryption')
            ->andReturn(true);

        $twofaccount = factory(TwoFAccount::class)->make([
            $attribute => 'string',
        ]);

        $this->assertEquals('string', Crypt::decryptString($twofaccount->getAttributes()[$attribute]));
    }

    /**
     * Provide attributes to test for encryption
     */
    public function provideSensitiveAttributes() : array
    {
        return [
            [
                'legacy_uri'
            ],
            [
                'secret'
            ],
            [
                'account'
            ],
        ];
    }

    /**
     * @test
     * 
     * @dataProvider provideSensitiveAttributes
     */
    public function test_sensitive_attributes_are_returned_clear(string $attribute)
    {
        \Facades\App\Services\SettingService::shouldReceive('get')
            ->with('useEncryption')
            ->andReturn(false);

        $twofaccount = factory(TwoFAccount::class)->make();

        $this->assertEquals($twofaccount->getAttributes()[$attribute], $twofaccount->$attribute);
    }


    /**
     * @test
     * 
     * @dataProvider provideSensitiveAttributes
     */
    public function test_indecipherable_attributes_returns_masked_value(string $attribute)
    {
        \Facades\App\Services\SettingService::shouldReceive('get')
            ->with('useEncryption')
            ->andReturn(true);

        Crypt::shouldReceive('encryptString')
            ->andReturn('indecipherableString');

        $twofaccount = factory(TwoFAccount::class)->make();

        $this->assertEquals(__('errors.indecipherable'), $twofaccount->$attribute);
    }
}