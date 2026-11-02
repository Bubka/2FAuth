<?php

namespace Tests\Feature\Notifications;

use App\Models\User;
use App\Notifications\ResetPassword;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * ResetPasswordNotificationTest test class
 */
#[CoversClass(ResetPassword::class)]
class ResetPasswordNotificationTest extends FeatureTestCase
{
    #[Test]
    public function test_reset_link_ignores_forged_host_header()
    {
        $user = User::factory()->create();

        $this->app['request']->headers->set('Host', 'attacker.example');

        $mail = (new ResetPassword('test_token'))->toMail($user)->render();

        $this->assertStringNotContainsString('attacker.example', $mail);
        $this->assertStringContainsString(config('app.url') . '/user/password/reset?token=test_token', $mail);
    }
}
