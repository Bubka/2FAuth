<?php

namespace Tests\Feature\Notifications;

use App\Models\AuthLog;
use App\Models\User;
use App\Notifications\FailedLoginNotification;
use Illuminate\Notifications\Messages\MailMessage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * FailedLoginTest test class
 */
#[CoversClass(FailedLoginNotification::class)]
class FailedLoginNotificationTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Models\AuthLog
     */
    protected $authLog;

    /**
     * @var \App\Notifications\FailedLoginNotification
     */
    protected $failedLogin;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        AuthLog::factory()->for($this->user, 'authenticatable')->failedLogin()->create();

        $this->authLog     = AuthLog::first();
        $this->failedLogin = new FailedLoginNotification($this->authLog);
    }

    #[Test]
    public function test_it_renders_to_email()
    {

        $mail = $this->failedLogin->toMail($this->user);

        $this->assertInstanceOf(MailMessage::class, $mail);
    }

    #[Test]
    public function test_rendered_email_contains_expected_data()
    {
        $mail = $this->failedLogin->toMail($this->user)->render();

        $this->assertStringContainsString(
            $this->authLog->login_at->toCookieString(),
            $mail
        );

        $this->assertStringContainsString(
            $this->authLog->ip_address,
            $mail
        );

        $this->assertStringContainsString(
            $this->user->name,
            $mail
        );

        $this->assertStringContainsString(
            __('message.browser_on_platform', ['browser' => 'Firefox', 'platform' => 'Windows']),
            $mail
        );
    }
}
