<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountGroupAssignment;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\ModelTestCase;

/**
 * TwoFAccountGroupAssignmentModelTest test class
 */
#[CoversClass(TwoFAccountGroupAssignment::class)]
class TwoFAccountGroupAssignmentModelTest extends ModelTestCase
{
    #[Test]
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(
            new TwoFAccountGroupAssignment,
            [
                'twofaccount_id',
                'group_id',
                'user_id',
            ],
            [],
            ['*'],
            [],
            [
                'twofaccount_id' => 'integer',
                'group_id'       => 'integer',
                'user_id'        => 'integer',
                'id'                  => 'int',
            ],
            []
        );
    }

    #[Test]
    public function test_twofaccount_relation()
    {
        $twoFAccountGroupAssignment = new TwoFAccountGroupAssignment;
        $account                    = $twoFAccountGroupAssignment->twofaccount();
        
        $this->assertBelongsToRelation($account, $twoFAccountGroupAssignment, new TwoFAccount(), 'twofaccount_id');
    }

    #[Test]
    public function test_group_relation()
    {
        $twoFAccountGroupAssignment = new TwoFAccountGroupAssignment;
        $group                      = $twoFAccountGroupAssignment->group();
        
        $this->assertBelongsToRelation($group, $twoFAccountGroupAssignment, new Group(), 'group_id');
    }

    #[Test]
    public function test_user_relation()
    {
        $twoFAccountGroupAssignment = new TwoFAccountGroupAssignment;
        $user                      = $twoFAccountGroupAssignment->user();
        
        $this->assertBelongsToRelation($user, $twoFAccountGroupAssignment, new User(), 'user_id');
    }
}
