<?php

namespace Tests\Feature\Listeners;

use App\Events\TwoFAccountShareRevoked;
use App\Listeners\DeleteRevokedTwoFAccountUserFavorites;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountUserFavorite;
use App\Models\User;
use App\Services\TwoFAccountShareService;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * DeleteRevokedTwoFAccountUserFavoritesTest test class
 */
#[CoversClass(DeleteRevokedTwoFAccountUserFavorites::class)]
class DeleteRevokedTwoFAccountUserFavoritesTest extends FeatureTestCase
{
    #[Test]
    public function test_it_deletes_users_favorites_of_revoked_shares()
    {
        $owner       = User::factory()->create();
        $targetUserA = User::factory()->create();
        $targetUserB = User::factory()->create();
        $twofaccount = TwoFAccount::factory()->for($owner)->create();
        $service     = new TwoFAccountShareService;

        $service->shareWithUser($twofaccount, $owner, $targetUserA);
        $service->shareWithUser($twofaccount, $owner, $targetUserB);

        TwoFAccountUserFavorite::create([
            'user_id'        => $targetUserA->id,
            'twofaccount_id' => $twofaccount->id,
        ]);
        TwoFAccountUserFavorite::create([
            'user_id'        => $targetUserB->id,
            'twofaccount_id' => $twofaccount->id,
        ]);

        $service->revokeUserShare($twofaccount, $targetUserA);

        $this->assertDatabaseMissing('twofaccount_user_favorites', [
            'user_id'        => $targetUserA->id,
            'twofaccount_id' => $twofaccount->id,
        ]);
        $this->assertDatabaseHas('twofaccount_user_favorites', [
            'user_id'        => $targetUserB->id,
            'twofaccount_id' => $twofaccount->id,
        ]);
    }

    #[Test]
    public function test_it_listens_to_TwofaccountShareRevoked_event()
    {
        Event::fake();

        Event::assertListening(
            TwoFAccountShareRevoked::class,
            DeleteRevokedTwoFAccountUserFavorites::class
        );
    }
}
