<?php

namespace App\Services;

use App\Events\TwoFAccountShared;
use App\Events\TwoFAccountShareRevoked;
use App\Models\TwoFAccount;
use App\Models\TwoFAccountShare;
use App\Models\User;
use Illuminate\Support\Collection;

class TwoFAccountShareService
{
    /**
     * Share an account with several users and return per-user status.
     *
     * @param  Collection<int, User>  $targetUsers
     * @return Collection<int, array{user: User, created: bool}>
     */
    public function shareWithUsers(TwoFAccount $twofaccount, User $owner, Collection $targetUsers) : Collection
    {
        if ($this->isSharedWithAll($twofaccount)) {
            return $targetUsers->map(function (User $targetUser) {
                return [
                    'user'    => $targetUser,
                    'created' => false,
                ];
            });
        }

        return $targetUsers->map(function (User $targetUser) use ($twofaccount, $owner) {
            $result = $this->shareWithUser($twofaccount, $owner, $targetUser);

            return [
                'user'    => $targetUser,
                'created' => $result['created'],
            ];
        })->tap(function (Collection $results) use ($twofaccount, $owner) {
            $recipients = $results->where('created', true)->pluck('user')->values();

            if ($recipients->isNotEmpty()) {
                event(new TwoFAccountShared($twofaccount, $owner, $recipients, TwoFAccountShare::SCOPE_USER));
            }
        });
    }

    /**
     * Share an account with one user
     *
     * @return array{share: TwoFAccountShare|null, created: bool}
     */
    public function shareWithUser(TwoFAccount $twofaccount, User $owner, User $targetUser) : array
    {
        if ($this->isSharedWithAll($twofaccount)) {
            return [
                'share'   => null,
                'created' => false,
            ];
        }

        $share = TwoFAccountShare::firstOrCreate(
            [
                'twofaccount_id'      => $twofaccount->id,
                'scope'               => TwoFAccountShare::SCOPE_USER,
                'shared_with_user_id' => $targetUser->id,
            ],
            [
                'created_by_user_id' => $owner->id,
            ]
        );

        event(new TwoFAccountShared($twofaccount, $owner, collect([$targetUser]), TwoFAccountShare::SCOPE_USER));

        return [
            'share'   => $share,
            'created' => $share->wasRecentlyCreated,
        ];
    }

    /**
     * Revoke a single user share
     *
     * @return int number of revoked rows
     */
    public function revokeUserShare(TwoFAccount $twofaccount, User $targetUser) : int
    {
        $deletedRows = TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_USER)
            ->where('shared_with_user_id', $targetUser->id)
            ->delete();

        if ($deletedRows > 0 && $twofaccount->user) {
            event(new TwoFAccountShareRevoked($twofaccount, $twofaccount->user, collect([$targetUser]), TwoFAccountShare::SCOPE_USER));
        }

        return $deletedRows;
    }

    /**
     * Revoke all user shares
     *
     * @return int number of revoked rows
     */
    public function revokeAllUserShares(TwoFAccount $twofaccount, bool $dispatchEvent = true) : int
    {
        $recipients = $this->explicitSharedUsers($twofaccount);

        $deletedRows = TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_USER)
            ->delete();

        if ($dispatchEvent && $deletedRows > 0 && $twofaccount->user && $recipients->isNotEmpty()) {
            event(new TwoFAccountShareRevoked($twofaccount, $twofaccount->user, $recipients, TwoFAccountShare::SCOPE_USER));
        }

        return $deletedRows;
    }

    /**
     * Share an account with all users
     *
     * @return array{share: TwoFAccountShare, created: bool}
     */
    public function shareWithAll(TwoFAccount $twofaccount, User $owner) : array
    {
        $share = TwoFAccountShare::firstOrCreate(
            [
                'twofaccount_id'      => $twofaccount->id,
                'scope'               => TwoFAccountShare::SCOPE_ALL_USERS,
                'shared_with_user_id' => null,
            ],
            [
                'created_by_user_id' => $owner->id,
            ]
        );

        if ($share->wasRecentlyCreated) {
            $recipients = User::where('id', '!=', $owner->id)->orderBy('id')->get();

            if ($recipients->isNotEmpty()) {
                event(new TwoFAccountShared($twofaccount, $owner, $recipients, TwoFAccountShare::SCOPE_ALL_USERS));
            }
        }

        return [
            'share'   => $share,
            'created' => $share->wasRecentlyCreated,
        ];
    }

    /**
     * Unshare an account with all users
     *
     * @return int number of deleted rows
     */
    public function unshareWithAll(TwoFAccount $twofaccount) : int
    {
        $actor = $twofaccount->user;

        $deletedRows = TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_ALL_USERS)
            ->delete();

        if ($deletedRows > 0 && $actor) {
            $recipients = User::where('id', '!=', $actor->id)->orderBy('id')->get();

            if ($recipients->isNotEmpty()) {
                event(new TwoFAccountShareRevoked($twofaccount, $actor, $recipients, TwoFAccountShare::SCOPE_ALL_USERS));
            }
        }

        return $deletedRows;
    }

    /**
     * Get explicitly shared users for a twofaccount, i.e. excluding implicit shares via "all users" share
     *
     * @return Collection<int, User>
     */
    public function explicitSharedUsers(TwoFAccount $twofaccount) : Collection
    {
        return User::query()
            ->whereIn(
                'id',
                TwoFAccountShare::query()
                    ->where('twofaccount_id', $twofaccount->id)
                    ->where('scope', TwoFAccountShare::SCOPE_USER)
                    ->whereNotNull('shared_with_user_id')
                    ->pluck('shared_with_user_id')
            )
            ->orderBy('id')
            ->get();
    }

    /**
     * Determine if a twofaccount is shared with all users
     */
    public function isSharedWithAll(TwoFAccount $twofaccount) : bool
    {
        return TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_ALL_USERS)
            ->exists();
    }
}
