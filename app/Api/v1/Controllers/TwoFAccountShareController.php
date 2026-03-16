<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\TwoFAccountShareStoreRequest;
use App\Http\Controllers\Controller;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Services\TwoFAccountShareService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TwoFAccountShareController extends Controller
{
    public function __construct(private readonly TwoFAccountShareService $twoFAccountShareService) {}

    /**
     * List account shares.
     */
    public function index(TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        $isSharedWithAll = $this->twoFAccountShareService->isSharedWithAll($twofaccount);

        $users = $isSharedWithAll
            ? collect([])
            : $this->twoFAccountShareService->explicitSharedUsers($twofaccount)
                ->map(function (User $user) {
                    return [
                        'id' => $user->id,
                    ];
                })
                ->values();

        return response()->json([
            'is_shared_with_all' => $isSharedWithAll,
            'users'              => $users,
        ], 200);
    }

    /**
     * Share an account with a user.
     */
    public function store(TwoFAccountShareStoreRequest $request, TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        if ($this->twoFAccountShareService->isSharedWithAll($twofaccount)) {
            return response()->json([
                'message' => 'conflict',
                'reason'  => ['twofaccount' => 'This account is already shared with all users.'],
            ], 409);
        }

        $targetUsers = User::query()
            ->whereIn('id', $request->validated('ids'))
            ->orderBy('id')
            ->get();

        if ($targetUsers->contains(fn (User $targetUser) => $twofaccount->isOwnedBy($targetUser))) {
            return response()->json([
                'message' => 'bad request',
                'reason'  => ['ids' => __('validation.different', ['attribute' => 'ids', 'value' => 'owner'])],
            ], 422);
        }

        $results = $this->twoFAccountShareService->shareWithUsers($twofaccount, $request->user(), $targetUsers);
        $created = $results->where('created', true)->count();

        return response()->json([
            'users' => $results
                ->map(fn (array $result) => [
                    'id' => $result['user']->id,
                ])
                ->values(),
            'twofaccount_id' => $twofaccount->id,
        ], $created > 0 ? 201 : 200);
    }

    /**
     * Revoke share for a user.
     */
    public function destroy(Request $request, TwoFAccount $twofaccount, User $user) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        if ($this->twoFAccountShareService->isSharedWithAll($twofaccount)) {
            return response()->json([
                'message' => 'conflict',
                'reason'  => ['twofaccount' => 'This account is shared with all users.'],
            ], 409);
        }

        $this->twoFAccountShareService->revokeUserShare($twofaccount, $user);

        return response()->json(null, 204);
    }

    /**
     * Revoke all explicit user shares for the account.
     */
    public function destroyAllUsers(TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        if ($this->twoFAccountShareService->isSharedWithAll($twofaccount)) {
            return response()->json([
                'message' => 'conflict',
                'reason'  => ['twofaccount' => 'This account is shared with all users.'],
            ], 409);
        }

        $this->twoFAccountShareService->revokeAllUserShares($twofaccount);

        return response()->json(null, 204);
    }

    /**
     * Share account with all users.
     */
    public function shareAll(Request $request, TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        $this->twoFAccountShareService->revokeAllUserShares($twofaccount);

        $result = $this->twoFAccountShareService->shareWithAll($twofaccount, $request->user());

        return response()->json([
            'is_shared_with_all' => true,
            'twofaccount_id'     => $twofaccount->id,
        ], $result['created'] ? 201 : 200);
    }

    /**
     * Revoke global share.
     */
    public function unshareAll(TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        if (! $this->twoFAccountShareService->isSharedWithAll($twofaccount)) {
            return response()->json([
                'message' => 'conflict',
                'reason'  => ['twofaccount' => 'This account is not shared with all users.'],
            ], 409);
        }

        $this->twoFAccountShareService->unshareWithAll($twofaccount);

        return response()->json(null, 204);
    }
}
