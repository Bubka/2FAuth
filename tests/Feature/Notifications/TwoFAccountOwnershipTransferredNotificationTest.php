<?php

namespace Tests\Feature\Notifications;

use App\Models\TwoFAccount;
use App\Models\User;
use App\Notifications\TwoFAccountOwnershipTransferredNotification;
use Illuminate\Notifications\Messages\MailMessage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

#[CoversClass(TwoFAccountOwnershipTransferredNotification::class)]
class TwoFAccountOwnershipTransferredNotificationTest extends FeatureTestCase
{
    #[Test]
    public function test_it_renders_to_email() : void
    {
        $previousOwner = User::factory()->create();
        $newOwner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($previousOwner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $notification = new TwoFAccountOwnershipTransferredNotification($twofaccount, $previousOwner);

        $this->assertInstanceOf(MailMessage::class, $notification->toMail($newOwner));
    }

    #[Test]
    public function test_rendered_email_contains_expected_data() : void
    {
        $previousOwner = User::factory()->create(['name' => 'Alice']);
        $newOwner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($previousOwner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $notification = new TwoFAccountOwnershipTransferredNotification($twofaccount, $previousOwner);
        $mail = $notification->toMail($newOwner)->render();

        $this->assertStringContainsString('Alice', $mail);
        $this->assertStringContainsString('Github (alice@example.org)', $mail);
        $this->assertStringContainsString(__('link.go_to_2fauth_host'), $mail);
    }
}
