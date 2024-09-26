<?php

namespace Tests\Feature\Notifications;

use App\Models\User;
use App\Notifications\WebauthnRecoveryNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * WebauthnRecoveryNotificationTest test class
 */
#[CoversClass(WebauthnRecoveryNotification::class)]
class WebauthnRecoveryNotificationTest extends FeatureTestCase
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Notifications\WebauthnRecoveryNotification
     */
    protected $webauthnRecoveryNotification;

    public function setUp() : void
    {
        parent::setUp();

        $this->user                         = User::factory()->create();
        $this->webauthnRecoveryNotification = new WebauthnRecoveryNotification('test_token');
    }

    #[Test]
    public function test_it_renders_to_email()
    {
        $mail = $this->webauthnRecoveryNotification->toMail($this->user);

        $this->assertInstanceOf(MailMessage::class, $mail);
    }

    #[Test]
    public function test_rendered_email_contains_expected_data()
    {
        $mail = $this->webauthnRecoveryNotification->toMail($this->user)->render();

        $this->assertStringContainsString(
            'http://localhost/webauthn/recover?token=test_token&amp;email=' . urlencode($this->user->email),
            $mail
        );

        $this->assertStringContainsString(
            Lang::get('Recover Account'),
            $mail
        );

        $this->assertStringContainsString(
            Lang::get(
                'You are receiving this email because we received an account recovery request for your account.'
            ),
            $mail
        );

        $this->assertStringContainsString(
            Lang::get(
                'This recovery link will expire in :count minutes.',
                ['count' => config('auth.passwords.webauthn.expire')]
            ),
            $mail
        );

        $this->assertStringContainsString(
            Lang::get('If you did not request an account recovery, no further action is required.'),
            $mail
        );
    }
}
