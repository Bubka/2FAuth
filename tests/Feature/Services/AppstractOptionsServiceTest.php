<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use Illuminate\Support\Facades\DB;

class AppstractOptionsServiceTest extends FeatureTestCase
{
    /**
     * App\Services\SettingServiceInterface $settingService
     */
    protected $settingService;

    private const KEY = 'key';
    private const VALUE = 'value';
    private const SETTING_NAME = 'MySetting';
    private const SETTING_NAME_ALT = 'MySettingAlt';
    private const SETTING_VALUE_STRING = 'MyValue';
    private const SETTING_VALUE_TRUE_TRANSFORMED = '{{1}}';
    private const SETTING_VALUE_FALSE_TRANSFORMED = '{{}}';
    private const SETTING_VALUE_INT = 10;


    /**
     * @test
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->settingService = $this->app->make('App\Services\SettingServiceInterface');
    }


    /**
     * @test
     */
    public function test_get_string_setting_returns_correct_value()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_STRING)]
        );

        $this->assertEquals(self::SETTING_VALUE_STRING, $this->settingService->get(self::SETTING_NAME));
    }


    /**
     * @test
     */
    public function test_get_boolean_setting_returns_true()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_TRUE_TRANSFORMED)]
        );

        $this->assertEquals(true, $this->settingService->get(self::SETTING_NAME));
    }


    /**
     * @test
     */
    public function test_get_boolean_setting_returns_false()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_FALSE_TRANSFORMED)]
        );

        $this->assertEquals(false, $this->settingService->get(self::SETTING_NAME));
    }


    /**
     * @test
     */
    public function test_get_int_setting_returns_int()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_INT)]
        );

        $value = $this->settingService->get(self::SETTING_NAME);

        $this->assertEquals(self::SETTING_VALUE_INT, $value);
        $this->assertIsInt($value);
    }


    /**
     * @test
     */
    public function test_all_returns_native_and_user_settings()
    {
        $native_options = config('2fauth.options');

        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_STRING)]
        );

        $all = $this->settingService->all();

        $this->assertCount(count($native_options)+1, $all);

        $this->assertArrayHasKey(self::SETTING_NAME, $all);
        $this->assertEquals($all[self::SETTING_NAME], self::SETTING_VALUE_STRING);

        foreach ($native_options as $key => $val) {
            $this->assertArrayHasKey($key, $all);
            $this->assertEquals($all[$key], $val);
        }

    }


    /**
     * @test
     */
    public function test_set_setting_persist_correct_value()
    {
        $value = $this->settingService->set(self::SETTING_NAME, self::SETTING_VALUE_STRING);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_STRING
        ]);
    }


    /**
     * @test
     */
    public function test_set_array_of_settings_persist_correct_values()
    {
        $value = $this->settingService->set([
            self::SETTING_NAME => self::SETTING_VALUE_STRING,
            self::SETTING_NAME_ALT => self::SETTING_VALUE_INT,
        ]);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_STRING
        ]);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME_ALT,
            self::VALUE => self::SETTING_VALUE_INT
        ]);
    }


    /**
     * @test
     */
    public function test_set_true_setting_persist_transformed_boolean()
    {
        $value = $this->settingService->set(self::SETTING_NAME, true);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_TRUE_TRANSFORMED
        ]);
    }


    /**
     * @test
     */
    public function test_set_false_setting_persist_transformed_boolean()
    {
        $value = $this->settingService->set(self::SETTING_NAME, false);

        $this->assertDatabaseHas('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_FALSE_TRANSFORMED
        ]);
    }


    /**
     * @test
     */
    public function test_del_remove_setting_from_db()
    {
        DB::table('options')->insert(
            [self::KEY => self::SETTING_NAME, self::VALUE => strval(self::SETTING_VALUE_STRING)]
        );

        $value = $this->settingService->delete(self::SETTING_NAME);

        $this->assertDatabaseMissing('options', [
            self::KEY => self::SETTING_NAME,
            self::VALUE => self::SETTING_VALUE_STRING
        ]);
    }
}