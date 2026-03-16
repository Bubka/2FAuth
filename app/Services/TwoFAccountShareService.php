<?php

namespace App\Services;

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
        });
    }

    /**
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

        return [
            'share'   => $share,
            'created' => $share->wasRecentlyCreated,
        ];
    }

    /**
     * @return int number of revoked rows
     */
    public function revokeUserShare(TwoFAccount $twofaccount, User $targetUser) : int
    {
        return TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_USER)
            ->where('shared_with_user_id', $targetUser->id)
            ->delete();
    }

    /**
     * @return int number of revoked rows
     */
    public function revokeAllUserShares(TwoFAccount $twofaccount) : int
    {
        return TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_USER)
            ->delete();
    }

    /**
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

        return [
            'share'   => $share,
            'created' => $share->wasRecentlyCreated,
        ];
    }

    /**
     * @return int number of deleted rows
     */
    public function unshareWithAll(TwoFAccount $twofaccount) : int
    {
        return TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_ALL_USERS)
            ->delete();
    }

    /**
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

    public function isSharedWithAll(TwoFAccount $twofaccount) : bool
    {
        return TwoFAccountShare::query()
            ->where('twofaccount_id', $twofaccount->id)
            ->where('scope', TwoFAccountShare::SCOPE_ALL_USERS)
            ->exists();
    }
}
