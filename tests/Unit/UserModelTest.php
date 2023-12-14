<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\ModelTestCase;

/**
 * UserModelTest test class
 */
#[CoversClass(User::class)]
class UserModelTest extends ModelTestCase
{
    /**
     * @test
     */
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new User(),
            ['name', 'email', 'password', 'oauth_id', 'oauth_provider'],
            ['password', 'remember_token'],
            ['*'],
            [],
            [
                'id'                 => 'int',
                'email_verified_at'  => 'datetime',
                'password'           => 'hashed',
                'is_admin'           => 'boolean',
                'twofaccounts_count' => 'integer',
                'groups_count'       => 'integer',
            ]
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

    /**
     * @test
     */
    public function test_twofaccounts_relation()
    {
        $user     = new User();
        $accounts = $user->twofaccounts();
        $this->assertHasManyRelation($accounts, $user, new TwoFAccount());
    }

    /**
     * @test
     */
    public function test_groups_relation()
    {
        $user   = new User();
        $groups = $user->groups();
        $this->assertHasManyRelation($groups, $user, new Group());
    }
}
