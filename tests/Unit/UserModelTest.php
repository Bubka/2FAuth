<?php

namespace Tests\Unit;

use App\User;
use Tests\ModelTestCase;

/**
 * @covers \App\User
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
        $user = factory(User::class)->make([
            'email' => 'UPPERCASE@example.COM',
        ]);

        $this->assertEquals(strtolower('UPPERCASE@example.COM'), $user->email);
    }
}