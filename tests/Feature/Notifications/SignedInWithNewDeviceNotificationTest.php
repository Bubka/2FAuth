<?php

namespace Tests\Feature\Notifications;

use App\Models\AuthLog;
use App\Models\User;
use App\Notifications\SignedInWithNewDeviceNotification;
use Illuminate\Notifications\Messages\MailMessage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * SignedInWithNewDeviceNotificationTest test class
 */
#[CoversClass(SignedInWithNewDeviceNotification::class)]
class SignedInWithNewDeviceNotificationTest extends FeatureTestCase
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
     * @var \App\Notifications\SignedInWithNewDeviceNotification
     */
    protected $signedInWithNewDevice;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        AuthLog::factory()->for($this->user, 'authenticatable')->failedLogin()->create();

        $this->authLog               = AuthLog::first();
        $this->signedInWithNewDevice = new SignedInWithNewDeviceNotification($this->authLog);
    }

    #[Test]
    public function test_it_renders_to_email()
    {
        $mail = $this->signedInWithNewDevice->toMail($this->user);

        $this->assertInstanceOf(MailMessage::class, $mail);
    }

    #[Test]
    public function test_rendered_email_contains_expected_data()
    {
        $mail = $this->signedInWithNewDevice->toMail($this->user)->render();

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
