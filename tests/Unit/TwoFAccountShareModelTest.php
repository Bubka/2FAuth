<?php

namespace Tests\Unit;

use App\Models\TwoFAccount;
use App\Models\TwoFAccountShare;
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
        $account          = $twoFAccountShare->twofaccount();
        
        $this->assertBelongsToRelation($account, $twoFAccountShare, new TwoFAccount, 'twofaccount_id');
    }

    #[Test]
    public function test_sharedWithUser_relation()
    {
        $twoFAccountShare = new TwoFAccountShare;
        $account          = $twoFAccountShare->sharedWithUser();
        
        $this->assertBelongsToRelation($account, $twoFAccountShare, new TwoFAccount, 'shared_with_user_id');
    }

    #[Test]
    public function test_createdByUser_relation()
    {
        $twoFAccountShare = new TwoFAccountShare;
        $account          = $twoFAccountShare->createdByUser();
        
        $this->assertBelongsToRelation($account, $twoFAccountShare, new TwoFAccount, 'created_by_user_id');
    }
}
