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
                    'setting_2' => 'value_2',
                ])
            ->assertStatus(200)
            ->assertJson([
                'message' => __('settings.forms.setting_saved'),
                'settings' => [
                    'setting_1' => 'value_1',
                    'setting_2' => 'value_2',
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
        option(['setting_2' => 'value_2']);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/settings/options')
            ->assertStatus(200)
            ->assertJson([
                'settings' => [
                    [
                        'id' => '1',
                        'key' => 'setting_1',
                        'value' => 'value_1'
                    ],
                    [
                        'id' => '2',
                        'key' => 'setting_2',
                        'value' => 'value_2' 
                    ]
                ]
            ]);
    }

}
