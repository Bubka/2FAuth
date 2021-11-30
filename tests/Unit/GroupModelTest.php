<?php

namespace Tests\Unit;

use App\Group;
use App\TwoFAccount;
use App\Events\GroupDeleting;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\ModelTestCase;

/**
 * @covers \App\Group
 */
class GroupModelTest extends ModelTestCase
{

    /**
     * @test
     */
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(
            new Group(),
            ['name'],
            ['created_at', 'updated_at'],
            ['*'],
            [],
            ['id' => 'int', 'twofaccounts_count' => 'integer',],
            ['deleting' => GroupDeleting::class]
        );
    }


    /**
     * @test
     */
    public function test_groups_relation()
    {
        $group = new Group();
        $accounts = $group->twofaccounts();
        $this->assertHasManyRelation($accounts, $group, new TwoFAccount());
    }
}