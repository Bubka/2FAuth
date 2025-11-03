<?php

namespace Tests\Feature\Models;

use App\Models\AuthLog;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * UserModelTest test class
 */
#[CoversClass(AuthLog::class)]
class AuthLogModelTest extends FeatureTestCase
{
    #[Test]
    public function test_equals_returns_true()
    {
        $user        = User::factory()->create();
        $lastAuthLog = AuthLog::factory()->for($user, 'authenticatable')->create();

        $this->assertTrue($lastAuthLog->equals($lastAuthLog));
    }
}
