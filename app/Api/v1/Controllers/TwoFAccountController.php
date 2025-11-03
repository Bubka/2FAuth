<?php

namespace App\Api\v1\Controllers;

use App\Api\v1\Requests\ReorderRequest;
use App\Api\v1\Requests\TwoFAccountBatchRequest;
use App\Api\v1\Requests\TwoFAccountDynamicRequest;
use App\Api\v1\Requests\TwoFAccountExportRequest;
use App\Api\v1\Requests\TwoFAccountImportRequest;
use App\Api\v1\Requests\TwoFAccountIndexRequest;
use App\Api\v1\Requests\TwoFAccountStoreRequest;
use App\Api\v1\Requests\TwoFAccountUpdateRequest;
use App\Api\v1\Requests\TwoFAccountUriRequest;
use App\Api\v1\Resources\TwoFAccountCollection;
use App\Api\v1\Resources\TwoFAccountExportCollection;
use App\Api\v1\Resources\TwoFAccountReadResource;
use App\Api\v1\Resources\TwoFAccountStoreResource;
use App\Facades\Groups;
use App\Facades\TwoFAccounts;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TwoFAccountController extends Controller
{
    /**
     * List all resources
     *
     * @return \App\Api\v1\Resources\TwoFAccountCollection
     */
    public function index(TwoFAccountIndexRequest $request)
    {
        // Quick fix for #176
        if (config('auth.defaults.guard') === 'reverse-proxy-guard' && User::count() === 1) {
            if (TwoFAccount::orphans()->exists()) {
                $twofaccounts = TwoFAccount::orphans()->get();
                TwoFAccounts::setUser($twofaccounts, $request->user());
            }
        }

        $validated = $request->validated();

        return Arr::has($validated, 'ids')
            ? new TwoFAccountCollection($request->user()->twofaccounts()->whereIn('id', Helpers::commaSeparatedToArray($validated['ids']))->get()->sortBy('order_column'))
            : new TwoFAccountCollection($request->user()->twofaccounts->sortBy('order_column'));
    }

    /**
     * Display a 2FA account
     *
     * @return \App\Api\v1\Resources\TwoFAccountReadResource
     */
    public function show(TwoFAccount $twofaccount)
    {
        $this->authorize('view', $twofaccount);

        // $icon = $twofaccount->icon;
        // $iconRes = $twofaccount->icon()->get();

        return new TwoFAccountReadResource($twofaccount);
    }

    /**
     * Store a new 2FA account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TwoFAccountDynamicRequest $request)
    {
        $this->authorize('create', TwoFAccount::class);

        // Two possible cases :
        // - The most common case, an URI is provided by the QuickForm, thanks to a QR code live scan or file upload
        //     -> We use that URI to define the account
        // - The advanced form has been used and all individual parameters
        //     -> We use the parameters array to define the account

        $validated   = $request->validated();
        $twofaccount = new TwoFAccount;

        if (Arr::has($validated, 'uri')) {
            $twofaccount->fillWithURI($validated['uri'], Arr::get($validated, 'custom_otp') === TwoFAccount::STEAM_TOTP);
        } else {
            $twofaccount->fillWithOtpParameters($validated);
        }
        $request->user()->twofaccounts()->save($twofaccount);

        // Possible group association
        try {
            Groups::assign($twofaccount->id, $request->user(), Arr::get($validated, 'group_id', null));
        } catch (\Throwable $th) {
            // The group association might fail but we don't want the twofaccount
            // creation to be reverted so we do nothing here.
        }

        return (new TwoFAccountReadResource($twofaccount->refresh()))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update a 2FA account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TwoFAccountUpdateRequest $request, TwoFAccount $twofaccount)
    {
        $this->authorize('update', $twofaccount);

        $validated = $request->validated();

        $twofaccount->fillWithOtpParameters($validated, $twofaccount->icon && is_null(Arr::get($validated, 'icon', null)));
        $request->user()->twofaccounts()->save($twofaccount);

        // Possible group change
        $groupId = Arr::get($validated, 'group_id', null);
        if ($twofaccount->group_id != $groupId) {
            if ((int) $groupId === 0) {
                TwoFAccounts::withdraw($twofaccount->id);
            } else {
                try {
                    Groups::assign($twofaccount->id, $request->user(), $groupId);
                } catch (ModelNotFoundException $exc) {
                    // The destination group no longer exists, the twofaccount is withdrawn
                    TwoFAccounts::withdraw($twofaccount->id);
                }
            }
            $twofaccount->refresh();
        }

        return (new TwoFAccountReadResource($twofaccount))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Convert a migration resource to a valid TwoFAccounts collection
     *
     * @return \Illuminate\Http\JsonResponse|\App\Api\v1\Resources\TwoFAccountCollection
     */
    public function migrate(TwoFAccountImportRequest $request)
    {
        $validated = $request->validated();

        if (Arr::has($validated, 'file')) {
            $migrationResource = $request->file('file');

            return $migrationResource instanceof \Illuminate\Http\UploadedFile
                ? new TwoFAccountCollection(TwoFAccounts::migrate($migrationResource->get()))
                : response()->json(['message' => __('error.file_upload_failed')], 500);
        } else {
            return new TwoFAccountCollection(TwoFAccounts::migrate($request->payload));
        }
    }

    /**
     * Save 2FA accounts order
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(ReorderRequest $request)
    {
        $validated = $request->validated();

        $twofaccounts = TwoFAccount::whereIn('id', $validated['orderedIds'])->get();
        $this->authorize('updateEach', [new TwoFAccount, $twofaccounts]);

        TwoFAccount::setNewOrder($validated['orderedIds']);
        $orderedIds = $request->user()->twofaccounts->sortBy('order_column')->pluck('id');

        return response()->json([
            'message'    => 'order saved',
            'orderedIds' => $orderedIds,
        ], 200);
    }

    /**
     * Preview account using an uri, without any db moves
     *
     * @return \App\Api\v1\Resources\TwoFAccountStoreResource
     */
    public function preview(TwoFAccountUriRequest $request)
    {
        $twofaccount = new TwoFAccount;
        $twofaccount->fillWithURI($request->uri, $request->custom_otp === TwoFAccount::STEAM_TOTP);

        return new TwoFAccountStoreResource($twofaccount);
    }

    /**
     * Export accounts
     *
     * @return TwoFAccountExportCollection|\Illuminate\Http\JsonResponse
     */
    public function export(TwoFAccountExportRequest $request)
    {
        $validated = $request->validated();

        if ($this->tooManyIds($validated['ids'])) {
            return response()->json([
                'message' => 'bad request',
                'reason'  => [__('error.too_many_ids')],
            ], 400);
        }

        $twofaccounts = TwoFAccounts::export($validated['ids']);
        $this->authorize('viewEach', [new TwoFAccount, $twofaccounts]);

        return new TwoFAccountExportCollection($twofaccounts);
    }

    /**
     * Get a One-Time Password
     *
     * @param  string|null  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function otp(Request $request, $id = null)
    {
        $inputs = $request->all();

        // The request input is the ID of an existing account
        if ($id) {
            $twofaccount = TwoFAccount::findOrFail((int) $id);
            $this->authorize('view', $twofaccount);
        }

        // The request input is an uri
        elseif ($request->has('uri')) {
            // return 404 if uri is provided with any parameter other than otp_type
            if ((count($inputs) == 2 && $request->missing('custom_otp')) || count($inputs) > 2) {
                return response()->json([
                    'message' => 'bad request',
                    'reason'  => ['uri' => __('validation.onlyCustomOtpWithUri')],
                ], 400);
            } else {
                $validatedData = $request->validate((new TwoFAccountUriRequest)->rules());
                $twofaccount   = new TwoFAccount;
                $twofaccount->fillWithURI($validatedData['uri'], Arr::get($validatedData, 'custom_otp') === TwoFAccount::STEAM_TOTP, true);
            }
        }

        // The request inputs should define an account
        else {
            $validatedData = $request->validate((new TwoFAccountStoreRequest)->rules());
            $twofaccount   = new TwoFAccount;
            $twofaccount->fillWithOtpParameters($validatedData, true);
        }

        return response()->json($twofaccount->getOTP(), 200);
    }

    /**
     * A simple and light method to get the account count.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Request $request)
    {
        return response()->json(['count' => $request->user()->twofaccounts->count()], 200);
    }

    /**
     * Withdraw one or more accounts from their group
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(TwoFAccountBatchRequest $request)
    {
        $validated = $request->validated();

        if ($this->tooManyIds($validated['ids'])) {
            return response()->json([
                'message' => 'bad request',
                'reason'  => [__('error.too_many_ids')],
            ], 400);
        }

        $ids          = Helpers::commaSeparatedToArray($validated['ids']);
        $twofaccounts = TwoFAccount::whereIn('id', $ids)->get();

        $this->authorize('updateEach', [new TwoFAccount, $twofaccounts]);

        TwoFAccounts::withdraw($ids);

        return response()->json(['message' => 'accounts withdrawn'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(TwoFAccount $twofaccount)
    {
        $this->authorize('delete', $twofaccount);

        $twofaccount->delete();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchDestroy(TwoFAccountBatchRequest $request)
    {
        $validated = $request->validated();

        if ($this->tooManyIds($validated['ids'])) {
            return response()->json([
                'message' => 'bad request',
                'reason'  => [__('error.too_many_ids')],
            ], 400);
        }

        $ids          = Helpers::commaSeparatedToArray($validated['ids']);
        $twofaccounts = TwoFAccount::whereIn('id', $ids)->get();

        $this->authorize('deleteEach', [new TwoFAccount, $twofaccounts]);

        TwoFAccounts::delete($validated['ids']);

        return response()->json(null, 204);
    }

    /**
     * Checks ids length
     *
     * @param  string  $ids  comma-separated ids
     * @return bool whether or not the number of ids is acceptable
     */
    private function tooManyIds(string $ids) : bool
    {
        $arIds = explode(',', $ids, 100);
        $nb    = count($arIds);

        return $nb > 99 ? true : false;
    }
}
