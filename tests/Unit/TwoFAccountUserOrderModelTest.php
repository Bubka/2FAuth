<?php

namespace Tests\Unit;

use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountGroupAssignment;
use App\Models\TwoFAccountUserOrder;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\ModelTestCase;

/**
 * TwoFAccountUserOrderModelTest test class
 */
#[CoversClass(TwoFAccountUserOrder::class)]
class TwoFAccountUserOrderModelTest extends ModelTestCase
{
    #[Test]
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(
            new TwoFAccountUserOrder,
            [
                'twofaccount_id',
                'user_id',
                'position',
            ],
            [],
            ['*'],
            [],
            [
                'twofaccount_id' => 'integer',
                'user_id'        => 'integer',
                'position'       => 'integer',
                'id'                  => 'int',
            ],
            []
        );
    }

    #[Test]
    public function test_twofaccount_relation()
    {
        $twoFAccountUserOrder = new TwoFAccountUserOrder;
        $account              = $twoFAccountUserOrder->twofaccount();
        
        $this->assertBelongsToRelation($account, $twoFAccountUserOrder, new TwoFAccount(), 'twofaccount_id');
    }

    #[Test]
    public function test_user_relation()
    {
        $twoFAccountUserOrder = new TwoFAccountUserOrder;
        $user                 = $twoFAccountUserOrder->user();
        
        $this->assertBelongsToRelation($user, $twoFAccountUserOrder, new User(), 'user_id');
    }
}
