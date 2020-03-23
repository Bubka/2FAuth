<?php

namespace Tests\Unit\Settings;

use App\User;
use Tests\TestCase;

class OptionTest extends TestCase
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
     * test Settings storage via API
     *
     * @test
     */
    public function testSettingsStorage()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/settings/options', [
                    'setting_1' => 'value_1',
                    'setting_2' => true,
                    'setting_3' => false,
                ])
            ->assertStatus(200)
            ->assertJson([
                'message' => __('settings.forms.setting_saved'),
                'settings' => [
                    'setting_1' => 'value_1',
                    'setting_2' => true,
                    'setting_3' => false,
                ]
            ]);
    }


    /**
     * test Settings list fetching via API
     *
     * @test
     */
    public function testSettingsIndexListing()
    {
        option(['setting_1' => 'value_1']);
        option(['setting_2' => true]);
        option(['setting_3' => false]);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/settings/options')
            ->assertStatus(200)
            ->assertJson([
                'settings' => [
                    'setting_1' => 'value_1',
                    'setting_2' => true,
                    'setting_3' => false,
                ]
            ]);
    }

}
