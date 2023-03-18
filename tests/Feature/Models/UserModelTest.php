<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Tests\FeatureTestCase;

/**
 * @covers \App\Models\User
 */
class UserModelTest extends FeatureTestCase
{
    /**
     * @test
     */
    public function test_admin_scope_returns_only_admin()
    {
        User::factory()->count(4)->create();

        $firstAdmin = User::factory()->administrator()->create([
            'name' => 'first',
        ]);
        $secondAdmin = User::factory()->administrator()->create([
            'name' => 'secondAdmin',
        ]);

        $admins = User::admins()->get();

        $this->assertCount(2, $admins);
        $this->assertEquals($admins[0]->is_admin, true);
        $this->assertEquals($admins[1]->is_admin, true);
        $this->assertEquals($admins[0]->name, $firstAdmin->name);
        $this->assertEquals($admins[1]->name, $secondAdmin->name);
    }
}
