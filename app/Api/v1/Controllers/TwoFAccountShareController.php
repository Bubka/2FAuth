<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\TwoFAccountShareStoreRequest;
use App\Api\v1\Resources\UserShareRecipientResource;
use App\Facades\Settings;
use App\Http\Controllers\Controller;
use App\Models\TwoFAccount;
use App\Models\User;
use App\Services\TwoFAccountShareService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TwoFAccountShareController extends Controller
{
    public function __construct(private readonly TwoFAccountShareService $twoFAccountShareService) {}

    /**
     * List account shares.
     */
    public function index(TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        $isSharedWithAll = Settings::get('enableAllUsersSharingScope') && $this->twoFAccountShareService->isSharedWithAll($twofaccount);

        $users = $isSharedWithAll
            ? collect([])
            : $this->twoFAccountShareService->explicitSharedUsers($twofaccount)
                ->map(function (User $user) {
                    return new UserShareRecipientResource($user);
                })
                ->values();

        $payload = [
            'twofaccount_id'     => $twofaccount->id,
            'is_shared_with_all' => $isSharedWithAll,
        ];

        if (! $isSharedWithAll) {
            $payload['specific_users'] = $users;
        }

        return response()->json($payload, 200);
    }

    /**
     * Share an account with a user.
     */
    public function store(TwoFAccountShareStoreRequest $request, TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        if (Settings::get('enableAllUsersSharingScope') && $this->twoFAccountShareService->isSharedWithAll($twofaccount)) {
            return $this->shareAllConflictResponse();
        }

        $targetUsers = User::whereIn('id', $request->validated('user_ids'))->orderBy('id')->get();

        if ($targetUsers->contains(fn (User $targetUser) => $twofaccount->isOwnedBy($targetUser))) {
            return response()->json([
                'message' => 'bad request',
                'reason'  => ['user_ids' => __('validation.different', ['attribute' => 'user_ids', 'value' => 'owner'])],
            ], 422);
        }

        $results = $this->twoFAccountShareService->shareWithUsers($twofaccount, $request->user(), $targetUsers);
        $created = $results->where('created', true)->count();

        Log::info(sprintf('TwoFAccount #%s owned by User ID #%s has been shared by User ID #%s with %s users : %s', $twofaccount->id, $twofaccount->user_id, $request->user()->id, $created, implode(', ', $results->where('created', true)->map(fn (array $result) => $result['user']->id)->values()->all())));

        return response()->json([
            'twofaccount_id'     => $twofaccount->id,
            'is_shared_with_all' => false,
            'specific_users'     => $results
                ->map(fn (array $result) => (new UserShareRecipientResource($result['user']))->toArray($request))
                ->values(),
        ], $created > 0 ? 201 : 200);
    }

    /**
     * List all potential share recipients for a twofaccount
     *
     * @return AnonymousResourceCollection
     */
    public function recipients(TwoFAccount $twofaccount, Request $request)
    {
        $input = $request->input('filter.nameOrEmail', null);

        if ($request->has('filter.nameOrEmail') && ! $input) {
            return UserShareRecipientResource::collection(collect([]));
        }

        if (Validator::make(
            $request->all(),
            [
                'filter.nameOrEmail' => 'email',
            ]
        )->passes()
        ) {
            $filter = AllowedFilter::exact('nameOrEmail', 'email');
        } else {
            $filter = AllowedFilter::partial('nameOrEmail', 'name');
        }

        $users = QueryBuilder::for(User::whereNot('id', $request->user()->id))
            ->allowedFilters($filter)
            ->get();

        return UserShareRecipientResource::collection($users);
    }

    /**
     * Revoke share for a user.
     */
    public function destroy(Request $request, TwoFAccount $twofaccount, User $user) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        if (Settings::get('enableAllUsersSharingScope') && $this->twoFAccountShareService->isSharedWithAll($twofaccount)) {
            return $this->shareAllConflictResponse();
        }

        $this->twoFAccountShareService->revokeUserShare($twofaccount, $user);

        Log::info(sprintf('Share with User ID #%s of TwoFAccount #%s owned by User ID #%s has been revoked by User ID #%s', $user->id, $twofaccount->id, $twofaccount->user_id, $request->user()->id));

        return response()->json(null, 204);
    }

    /**
     * Revoke all account shares.
     */
    public function destroyAll(Request $request, TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        $this->twoFAccountShareService->revokeAllUserShares($twofaccount);

        if (Settings::get('enableAllUsersSharingScope')) {
            $this->twoFAccountShareService->unshareWithAll($twofaccount);
        }

        Log::info(sprintf('All shares of TwoFAccount #%s owned by User ID #%s have been revoked by User ID #%s', $twofaccount->id, $twofaccount->user_id, $request->user()->id));

        return response()->json(null, 204);
    }

    /**
     * Share account with all users.
     */
    public function shareAll(Request $request, TwoFAccount $twofaccount) : JsonResponse
    {
        $this->authorize('manageShares', $twofaccount);

        $this->twoFAccountShareService->revokeAllUserShares($twofaccount, false);

        $result = $this->twoFAccountShareService->shareWithAll($twofaccount, $request->user());

        Log::info(sprintf('TwoFAccount #%s owned by User ID #%s has been shared with all users by User ID #%s', $twofaccount->id, $twofaccount->user_id, $request->user()->id));

        return response()->json([
            'twofaccount_id'     => $twofaccount->id,
            'is_shared_with_all' => true,
        ], $result['created'] ? 201 : 200);
    }

    /**
     * Return a conflict response for actions that cannot be performed when the account is shared with all users.
     */
    private function shareAllConflictResponse() : JsonResponse
    {
        return response()->json([
            'message' => 'conflict',
            'reason'  => __('error.this_account_is_already_shared_with_all'),
        ], 409);
    }
}
