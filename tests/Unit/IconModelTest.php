<?php

namespace Tests\Unit;

use App\Models\Icon;
use App\Models\TwoFAccount;
use App\Services\SettingService;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Crypt;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\ModelTestCase;

/**
 * IconModelTest test class
 */
#[CoversClass(Icon::class)]
class IconModelTest extends ModelTestCase
{
    #[Test]
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(
            new Icon,
            ['name'],
            ['created_at', 'updated_at'],
            ['*'],
            [],
            [],
            [],
            ['created_at', 'updated_at'],
            \Illuminate\Database\Eloquent\Collection::class,
            'icons',
            'name',
            false
        );
    }

    #[Test]
    #[DataProvider('provideSensitiveAttributes')]
    public function test_sensitive_attributes_are_stored_encrypted(string $attribute)
    {
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->with('useEncryption')
                ->andReturn(true);
        });

        $icon = Icon::factory()->make([
            $attribute => base64_decode(OtpTestData::ICON_PNG_DATA),
        ]);

        $this->assertEquals(OtpTestData::ICON_PNG_DATA, Crypt::decryptString($icon->getAttributes()[$attribute]));
        $this->forgetMock(SettingService::class);
    }

    /**
     * Provide attributes to test for encryption
     */
    public static function provideSensitiveAttributes() : array
    {
        return [
            [
                'content',
            ],
        ];
    }

    #[Test]
    #[DataProvider('provideSensitiveAttributes')]
    public function test_sensitive_attributes_are_returned_clear(string $attribute)
    {
        $settingService = $this->mock(SettingService::class, function (MockInterface $settingService) {
            $settingService->shouldReceive('get')
                ->with('useEncryption')
                ->andReturn(false);
        });

        $icon = Icon::factory()->make([
            $attribute => base64_decode(OtpTestData::ICON_PNG_DATA),
        ]);

        $this->assertEquals($icon->getAttributes()[$attribute], base64_encode($icon->$attribute));
        $this->forgetMock(SettingService::class);
    }

    #[Test]
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

        $icon = Icon::factory()->make();

        $this->assertEquals(__('error.indecipherable'), $icon->$attribute);
        $this->forgetMock(SettingService::class);
    }

    #[Test]
    public function test_twofaccount_relation()
    {
        $model    = new TwoFAccount;
        $relation = $model->iconResource();

        $this->assertInstanceOf(HasOne::class, $relation);
        $this->assertEquals('name', $relation->getForeignKeyName());
    }
}
