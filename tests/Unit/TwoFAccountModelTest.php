<?php

namespace Tests\Unit;

use App\Events\TwoFAccountDeleted;
use App\Models\TwoFAccount;
use App\Services\SettingService;
use Illuminate\Support\Facades\Crypt;
use Mockery\MockInterface;
use Tests\ModelTestCase;

/**
 * @covers \App\Models\TwoFAccount
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
            ['id'      => 'int'],
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
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->with('useEncryption')
                ->andReturn(true);
        });

        $twofaccount = TwoFAccount::factory()->make([
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
                'legacy_uri',
            ],
            [
                'secret',
            ],
            [
                'account',
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
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->with('useEncryption')
                ->andReturn(false);
        });

        $twofaccount = TwoFAccount::factory()->make();

        $this->assertEquals($twofaccount->getAttributes()[$attribute], $twofaccount->$attribute);
    }

    /**
     * @test
     *
     * @dataProvider provideSensitiveAttributes
     */
    public function test_indecipherable_attributes_returns_masked_value(string $attribute)
    {
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->with('useEncryption')
                ->andReturn(true);
        });

        Crypt::shouldReceive('encryptString')
            ->andReturn('indecipherableString');

        $twofaccount = TwoFAccount::factory()->make();

        $this->assertEquals(__('errors.indecipherable'), $twofaccount->$attribute);
    }
}
