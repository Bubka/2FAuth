<?php

namespace Tests\Feature\Notifications;

use App\Models\User;
use App\Notifications\TestEmailSettingNotification;
use Illuminate\Notifications\Messages\MailMessage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * TestEmailSettingNotificationTest test class
 */
#[CoversClass(TestEmailSettingNotification::class)]
class TestEmailSettingNotificationTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Notifications\TestEmailSettingNotification
     */
    protected $testEmailSettingNotification;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user                         = User::factory()->create();
        $this->testEmailSettingNotification = new TestEmailSettingNotification('test_token');
    }

    #[Test]
    public function test_it_renders_to_email()
    {
        $mail = $this->testEmailSettingNotification->toMail($this->user);

        $this->assertInstanceOf(MailMessage::class, $mail);
    }

    #[Test]
    public function test_rendered_email_contains_expected_data()
    {
        $mail = $this->testEmailSettingNotification->toMail($this->user)->render();

        $this->assertStringContainsString(
            __('message.notifications.test_email_settings.success'),
            $mail
        );
    }
}
