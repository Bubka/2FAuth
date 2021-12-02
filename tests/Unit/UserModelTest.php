<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\ModelTestCase;

/**
 * @covers \App\Models\User
 */
class UserModelTest extends ModelTestCase
{

    /**
     * @test
     */
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new User(),
            ['name', 'email', 'password'],
            ['password', 'remember_token'],
            ['*'],
            [],
            ['id' => 'int', 'email_verified_at' => 'datetime']
        );
    }

    /**
     * @test
     */
    public function test_email_is_set_lowercased()
    {
        $user = User::factory()->make([
            'email' => 'UPPERCASE@example.COM',
        ]);

        $this->assertEquals(strtolower('UPPERCASE@example.COM'), $user->email);
    }
}