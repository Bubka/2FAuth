<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\ModelTestCase;

/**
 * UserModelTest test class
 */
#[CoversClass(User::class)]
class UserModelTest extends ModelTestCase
{
    #[Test]
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

    #[Test]
    public function test_email_is_set_lowercased()
    {
        $user = User::factory()->make([
            'email' => 'UPPERCASE@example.COM',
        ]);

        $this->assertEquals(strtolower('UPPERCASE@example.COM'), $user->email);
    }

    #[Test]
    public function test_twofaccounts_relation()
    {
        $user     = new User();
        $accounts = $user->twofaccounts();
        $this->assertHasManyRelation($accounts, $user, new TwoFAccount());
    }

    #[Test]
    public function test_groups_relation()
    {
        $user   = new User();
        $groups = $user->groups();
        $this->assertHasManyRelation($groups, $user, new Group());
    }

    #[Test]
    public function test_equals_is_true()
    {
        $user = User::factory()->make([
            'oauth_id'       => 'fake_id',
            'oauth_provider' => 'fake_provider',
        ]);
        $anotherUser = User::factory()->make([
            'name'           => $user->name,
            'email '         => $user->email,
            'oauth_id'       => $user->oauth_id,
            'oauth_provider' => $user->oauth_provider,
        ]);

        $this->assertTrue($user->equals($anotherUser));
    }

    #[Test]
    public function test_equals_is_false_if_name_differs()
    {
        $user = User::factory()->make([
            'oauth_id'       => 'fake_id',
            'oauth_provider' => 'fake_provider',
        ]);
        $anotherUser = User::factory()->make([
            'name'           => 'another name',
            'email '         => $user->email,
            'oauth_id'       => $user->oauth_id,
            'oauth_provider' => $user->oauth_provider,
        ]);

        $this->assertFalse($user->equals($anotherUser));
    }

    #[Test]
    public function test_equals_is_false_if_email_differs()
    {
        $user = User::factory()->make([
            'oauth_id'       => 'fake_id',
            'oauth_provider' => 'fake_provider',
        ]);
        $anotherUser = User::factory()->make([
            'name'           => $user->name,
            'email '         => 'another@email.com',
            'oauth_id'       => $user->oauth_id,
            'oauth_provider' => $user->oauth_provider,
        ]);

        $this->assertFalse($user->equals($anotherUser));
    }

    #[Test]
    public function test_equals_is_false_if_oauthid_differs()
    {
        $user = User::factory()->make([
            'oauth_id'       => 'fake_id',
            'oauth_provider' => 'fake_provider',
        ]);
        $anotherUser = User::factory()->make([
            'name'           => $user->name,
            'email '         => $user->email,
            'oauth_id'       => 'another_fake_id',
            'oauth_provider' => $user->oauth_provider,
        ]);

        $this->assertFalse($user->equals($anotherUser));
    }

    #[Test]
    public function test_equals_is_false_if_oauth_provider_differs()
    {
        $user = User::factory()->make([
            'oauth_id'       => 'fake_id',
            'oauth_provider' => 'fake_provider',
        ]);
        $anotherUser = User::factory()->make([
            'name'           => $user->name,
            'email '         => $user->email,
            'oauth_id'       => $user->oauth_id,
            'oauth_provider' => 'another_provider',
        ]);

        $this->assertFalse($user->equals($anotherUser));
    }
}
