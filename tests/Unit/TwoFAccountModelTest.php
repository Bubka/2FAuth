<?php

namespace Tests\Unit;

use App\Events\TwoFAccountDeleted;
use App\Helpers\Helpers;
use App\Models\TwoFAccount;
use App\Services\SettingService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Tests\ModelTestCase;

/**
 * TwoFAccountModelTest test class
 */
#[CoversClass(TwoFAccount::class)]
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
     */
    #[DataProvider('provideSensitiveAttributes')]
    public function test_sensitive_attributes_are_stored_encrypted(string $attribute)
    {
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->with('useEncryption')
                ->andReturn(true);
        });

        $twofaccount = TwoFAccount::factory()->make([
            $attribute => 'STRING==',
        ]);

        $this->assertEquals('STRING==', Crypt::decryptString($twofaccount->getAttributes()[$attribute]));
    }

    /**
     * Provide attributes to test for encryption
     */
    public static function provideSensitiveAttributes() : array
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
     */
    #[DataProvider('provideSensitiveAttributes')]
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
     */
    #[DataProvider('provideSensitiveAttributes')]
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

    /**
     * @test
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_secret_is_uppercased_and_padded_at_setup()
    {
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->with('useEncryption')
                ->andReturn(false);
        });

        $helpers = $this->mock('alias:' . Helpers::class, function (MockInterface $helpers) {
            $helpers->shouldReceive('PadToBase32Format')
                ->andReturn('YYYY====');
        });

        $twofaccount = TwoFAccount::factory()->make([
            'secret' => 'yyyy',
        ]);

        $this->assertEquals('YYYY====', $twofaccount->secret);
    }

    /**
     * @test
     */
    public function test_user_relation()
    {
        $model    = new TwoFAccount();
        $relation = $model->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('user_id', $relation->getForeignKeyName());
    }
}
