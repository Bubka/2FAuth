<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\TwoFAccountShareStoreRequest;
use App\Http\Controllers\Controller;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Services\TwoFAccountShareService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            return $this->shareAllConflictResponse('This account is already shared with all users.');
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

        Log::info(sprintf('TwoFAccount #%s owned by User ID #%s has been shared by User ID #%s with %s users : %s', $twofaccount->id, $twofaccount->user_id, $request->user()->id, $created, implode(', ', $results->where('created', true)->map(fn (array $result) => $result['user']->id)->values()->all())));

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
            return $this->shareAllConflictResponse('This account is already shared with all users.');
        }

        $this->twoFAccountShareService->revokeUserShare($twofaccount, $user);

        Log::info(sprintf('Share with User ID #%s of TwoFAccount #%s owned by User ID #%s has been revoked by User ID #%s', $user->id, $twofaccount->id, $twofaccount->user_id, $request->user()->id));

        return response()->json(null, 204);
    }

    /**
     * Revoke all explicit user shares for the account.
     */
    public function destroyAllUsers(Request $request, TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        if ($this->twoFAccountShareService->isSharedWithAll($twofaccount)) {
            return $this->shareAllConflictResponse();
        }

        $this->twoFAccountShareService->revokeAllUserShares($twofaccount);

        Log::info(sprintf('All user shares of TwoFAccount #%s owned by User ID #%s have been revoked by User ID #%s', $twofaccount->id, $twofaccount->user_id, $request->user()->id));

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

        Log::info(sprintf('TwoFAccount #%s owned by User ID #%s has been shared with all users by User ID #%s', $twofaccount->id, $twofaccount->user_id, $request->user()->id));

        return response()->json([
            'is_shared_with_all' => true,
            'twofaccount_id'     => $twofaccount->id,
        ], $result['created'] ? 201 : 200);
    }

    /**
     * Revoke global share.
     */
    public function unshareAll(Request $request, TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        if (! $this->twoFAccountShareService->isSharedWithAll($twofaccount)) {
            return response()->json([
                'message' => 'conflict',
                'reason'  => ['twofaccount' => 'This account is not shared with all users.'],
            ], 409);
        }

        $this->twoFAccountShareService->unshareWithAll($twofaccount);

        Log::info(sprintf('Global share of TwoFAccount #%s owned by User ID #%s has been revoked by User ID #%s', $twofaccount->id, $twofaccount->user_id, $request->user()->id));

        return response()->json(null, 204);
    }

    /**
     * Return a conflict response for actions that cannot be performed when the account is shared with all users.
     */
    private function shareAllConflictResponse(string $reason) : JsonResponse
    {
        return response()->json([
            'message' => 'conflict',
            'reason'  => ['twofaccount' => $reason],
        ], 409);
    }
}
