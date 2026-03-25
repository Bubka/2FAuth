<?php

namespace Tests\Feature\Notifications;

use App\Models\TwoFAccount;
use App\Models\User;
use App\Notifications\TwoFAccountSharedNotification;
use Illuminate\Notifications\Messages\MailMessage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(TwoFAccountSharedNotification::class)]
class TwoFAccountSharedNotificationTest extends FeatureTestCase
{
    #[Test]
    public function test_it_renders_to_email() : void
    {
        $owner = User::factory()->create();
        $recipient = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $notification = new TwoFAccountSharedNotification($twofaccount, $owner->name, false);

        $this->assertInstanceOf(MailMessage::class, $notification->toMail($recipient));
    }

    #[Test]
    public function test_rendered_email_contains_expected_data() : void
    {
        $owner = User::factory()->create(['name' => 'Alice']);
        $recipient = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $notification = new TwoFAccountSharedNotification($twofaccount, $owner->name, true);
        $mail = $notification->toMail($recipient)->render();

        $this->assertStringContainsString('Alice', $mail);
        $this->assertStringContainsString('Github (alice@example.org)', $mail);
        $this->assertStringContainsString(__('link.go_to_2fauth_host'), $mail);
    }
}
