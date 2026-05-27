<?php

namespace Tests\Unit;

use App\Models\TwoFAccount;
use App\Models\TwoFAccountShare;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\ModelTestCase;

/**
 * TwoFAccountShareModelTest test class
 */
#[CoversClass(TwoFAccountShare::class)]
class TwoFAccountShareModelTest extends ModelTestCase
{
    #[Test]
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(
            new TwoFAccountShare,
            [
                'twofaccount_id',
                'shared_with_user_id',
                'scope',
                'created_by_user_id',
            ],
            [],
            ['*'],
            [],
            [
                'twofaccount_id'      => 'integer',
                'shared_with_user_id' => 'integer',
                'created_by_user_id'  => 'integer',
                'id'                  => 'int',
            ],
            []
        );
    }

    #[Test]
    public function test_twofaccount_relation()
    {
        $twoFAccountShare = new TwoFAccountShare;
        $relation         = $twoFAccountShare->twofaccount();
        
        $this->assertBelongsToRelation($relation, $twoFAccountShare, new TwoFAccount, 'twofaccount_id');
        $this->assertInstanceOf(TwoFAccount::class, $relation->getRelated());
    }

    #[Test]
    public function test_sharedWithUser_relation()
    {
        $twoFAccountShare = new TwoFAccountShare;
        $relation         = $twoFAccountShare->sharedWithUser();
        
        $this->assertBelongsToRelation($relation, $twoFAccountShare, new User, 'shared_with_user_id');
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }

    #[Test]
    public function test_createdByUser_relation()
    {
        $twoFAccountShare = new TwoFAccountShare;
        $relation         = $twoFAccountShare->createdByUser();
        
        $this->assertBelongsToRelation($relation, $twoFAccountShare, new User, 'created_by_user_id');
        $this->assertInstanceOf(User::class, $relation->getRelated());
    }
}
