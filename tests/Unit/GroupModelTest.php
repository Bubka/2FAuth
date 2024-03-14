<?php

namespace Tests\Unit;

use App\Events\GroupDeleted;
use App\Events\GroupDeleting;
use App\Models\Group;
use App\Models\TwoFAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\ModelTestCase;

/**
 * GroupModelTest test class
 */
#[CoversClass(Group::class)]
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
            [
                'id'                 => 'int',
                'twofaccounts_count' => 'integer',
                'user_id'            => 'integer'
            ],
            [
                'deleting' => GroupDeleting::class,
                'deleted'  => GroupDeleted::class,
            ]
        );
    }

    /**
     * @test
     */
    public function test_twofaccounts_relation()
    {
        $group    = new Group();
        $accounts = $group->twofaccounts();
        $this->assertHasManyRelation($accounts, $group, new TwoFAccount());
    }

    /**
     * @test
     */
    public function test_user_relation()
    {
        $model    = new Group;
        $relation = $model->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('user_id', $relation->getForeignKeyName());
    }
}
