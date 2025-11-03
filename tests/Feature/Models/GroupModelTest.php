<?php

namespace Tests\Feature\Models;

use App\Models\Group;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * GroupModelTest test class
 */
#[CoversClass(Group::class)]
class GroupModelTest extends FeatureTestCase
{
    #[Test]
    public function test_scopeOrphans_retreives_accounts_without_owner()
    {
        $orphan = Group::factory()->create();

        $orphans = Group::orphans()->get();

        $this->assertCount(1, $orphans);
        $this->assertEquals($orphan->id, $orphans[0]->id);
    }
}
