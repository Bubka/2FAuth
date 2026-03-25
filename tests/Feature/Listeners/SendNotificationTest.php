<?php

namespace Tests\Feature\Listeners;

use App\Facades\TwoFAccounts;
use App\Listeners\SendTwoFAccountOwnershipTransferredNotification;
use App\Listeners\SendTwoFAccountSharedNotification;
use App\Listeners\SendTwoFAccountShareRevokedNotification;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Notifications\TwoFAccountOwnershipTransferredNotification;
use App\Notifications\TwoFAccountSharedNotification;
use App\Notifications\TwoFAccountShareRevokedNotification;
use App\Services\TwoFAccountShareService;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * SendNotificationTest test class
 */
#[CoversClass(SendTwoFAccountOwnershipTransferredNotification::class)]
#[CoversClass(SendTwoFAccountSharedNotification::class)]
#[CoversClass(SendTwoFAccountShareRevokedNotification::class)]
class SendNotificationTest extends FeatureTestCase
{
    #[Test]
    public function test_transferOwnership_notification_is_sent_to_user()
    {
        Notification::fake();

        $previousOwner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($previousOwner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $newOwner = User::factory()->create();
        $newOwner['preferences->notifyOnOwnershipTransfer'] = true;
        $newOwner->save();

        TwoFAccounts::transferOwnership($twofaccount, $newOwner);

        Notification::assertSentTo($newOwner, TwoFAccountOwnershipTransferredNotification::class);
    }

    #[Test]
    public function test_transferOwnership_notification_is_not_sent_to_user()
    {
        Notification::fake();

        $previousOwner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($previousOwner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $newOwner = User::factory()->create();
        $newOwner['preferences->notifyOnOwnershipTransfer'] = false;
        $newOwner->save();

        TwoFAccounts::transferOwnership($twofaccount, $newOwner);

        Notification::assertNothingSentTo($newOwner);
    }

    #[Test]
    public function test_shared_notification_is_sent_to_user()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $targetUser = User::factory()->create();
        $targetUser['preferences->notifyOnShare'] = true;
        $targetUser->save();

        $service = new TwoFAccountShareService();
        $service->shareWithUser($twofaccount, $owner, $targetUser);

        Notification::assertSentTo($targetUser, TwoFAccountSharedNotification::class);
    }

    #[Test]
    public function test_shared_notification_is_sent_only_to_user_allowing_notification()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $targetUser = User::factory()->create();
        $targetUser['preferences->notifyOnShare'] = true;
        $targetUser->save();

        $anotherUser = User::factory()->create();
        $anotherUser['preferences->notifyOnShare'] = false;
        $anotherUser->save();

        $service = new TwoFAccountShareService();
        $service->shareWithUsers($twofaccount, $owner, collect([$targetUser, $anotherUser]));

        Notification::assertSentTo($targetUser, TwoFAccountSharedNotification::class);
        Notification::assertNothingSentTo($anotherUser);
    }

    #[Test]
    public function test_shared_notification_is_not_sent_to_user()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $targetUser = User::factory()->create();
        $targetUser['preferences->notifyOnShare'] = false;
        $targetUser->save();

        $service = new TwoFAccountShareService();
        $service->shareWithUser($twofaccount, $owner, $targetUser);

        Notification::assertNothingSentTo($targetUser);
    }

    #[Test]
    public function test_shared_notification_is_not_sent_to_any_user()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $targetUser = User::factory()->create();
        $targetUser['preferences->notifyOnShare'] = false;
        $targetUser->save();

        $anotherUser = User::factory()->create();
        $anotherUser['preferences->notifyOnShare'] = false;
        $anotherUser->save();

        $service = new TwoFAccountShareService();
        $service->shareWithUsers($twofaccount, $owner, collect([$targetUser, $anotherUser]));

        Notification::assertNothingSentTo($targetUser);
        Notification::assertNothingSentTo($anotherUser);
    }

    #[Test]
    public function test_share_revoked_notification_is_sent_to_user()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $targetUser = User::factory()->create();
        $targetUser['preferences->notifyOnShare'] = false;
        $targetUser->save();

        $service = new TwoFAccountShareService();
        $service->shareWithUser($twofaccount, $owner, $targetUser);

        Notification::assertNothingSentTo($targetUser);

        $targetUser['preferences->notifyOnShare'] = true;
        $targetUser->save();

        $service->revokeUserShare($twofaccount, $targetUser);

        Notification::assertSentTo($targetUser, TwoFAccountShareRevokedNotification::class);
    }

    #[Test]
    public function test_share_revoked_notification_is_sent_only_to_user_allowing_notification()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $targetUser = User::factory()->create();
        $targetUser['preferences->notifyOnShare'] = false;
        $targetUser->save();

        $anotherUser = User::factory()->create();
        $anotherUser['preferences->notifyOnShare'] = false;
        $anotherUser->save();

        $service = new TwoFAccountShareService();
        $service->shareWithUsers($twofaccount, $owner, collect([$targetUser, $anotherUser]));

        Notification::assertNothingSentTo($targetUser);
        Notification::assertNothingSentTo($anotherUser);

        $anotherUser['preferences->notifyOnShare'] = true;
        $anotherUser->save();

        $service->revokeAllUserShares($twofaccount, true);

        Notification::assertNothingSentTo($targetUser);
        Notification::assertSentTo($anotherUser, TwoFAccountShareRevokedNotification::class);
    }

    #[Test]
    public function test_revoke_shared_notification_is_not_sent_to_user()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $targetUser = User::factory()->create();
        $targetUser['preferences->notifyOnShare'] = false;
        $targetUser->save();

        $service = new TwoFAccountShareService();
        $service->shareWithUser($twofaccount, $owner, $targetUser);
        $service->revokeUserShare($twofaccount, $targetUser);

        Notification::assertNothingSentTo($targetUser);
    }

    #[Test]
    public function test_share_revoked_notification_is_sent_to_any_user()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create([
            'service' => 'Github',
            'account' => 'alice@example.org',
        ]);

        $targetUser = User::factory()->create();
        $targetUser['preferences->notifyOnShare'] = false;
        $targetUser->save();

        $anotherUser = User::factory()->create();
        $anotherUser['preferences->notifyOnShare'] = false;
        $anotherUser->save();

        $service = new TwoFAccountShareService();
        $service->shareWithUsers($twofaccount, $owner, collect([$targetUser, $anotherUser]));
        $service->revokeAllUserShares($twofaccount, true);

        Notification::assertNothingSentTo($targetUser);
        Notification::assertNothingSentTo($anotherUser);
    }
}
