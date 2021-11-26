<?php

namespace App\Api\v1\Controllers;

use App\TwoFAccount;
use App\Exceptions\UndecipherableException;
use App\Api\v1\Requests\TwoFAccountReorderRequest;
use App\Api\v1\Requests\TwoFAccountStoreRequest;
use App\Api\v1\Requests\TwoFAccountUpdateRequest;
use App\Api\v1\Requests\TwoFAccountBatchRequest;
use App\Api\v1\Requests\TwoFAccountUriRequest;
use App\Api\v1\Requests\TwoFAccountDynamicRequest;
use App\Api\v1\Resources\TwoFAccountCollection;
use App\Api\v1\Resources\TwoFAccountReadResource;
use App\Api\v1\Resources\TwoFAccountStoreResource;
use App\Services\GroupService;
use App\Services\TwoFAccountService;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TwoFAccountController extends Controller
{
    /**
     * The TwoFAccount Service instance.
     */
    protected $twofaccountService;

    /**
     * The Group Service instance.
     */
    protected $groupService;


    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\TwoFAccountService  $twofaccountService
     * @param  \App\Services\GroupService  $groupService
     * @return void
     */
    public function __construct(TwoFAccountService $twofaccountService, GroupService $groupService)
    {
        $this->twofaccountService = $twofaccountService;
        $this->groupService = $groupService;
    }


    /**
     * List all resources
     *
     * @return \App\Api\v1\Resources\TwoFAccountCollection
     */
    public function index(Request $request)
    {
        return new TwoFAccountCollection(TwoFAccount::ordered()->get());
    }


    /**
     * Display a 2FA account
     *
     * @param  \App\TwoFAccount  $twofaccount
     * 
     * @return \App\Api\v1\Resources\TwoFAccountReadResource
     */
    public function show(TwoFAccount $twofaccount)
    {
        return new TwoFAccountReadResource($twofaccount);
    }


    /**
     * Store a new 2FA account
     *
     * @param  \App\Api\v1\Requests\TwoFAccountDynamicRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TwoFAccountDynamicRequest $request)
    {
        // Two possible cases :
        // - The most common case, an URI is provided by the QuickForm, thanks to a QR code live scan or file upload
        //     -> We use that URI to define the account
        // - The advanced form has been used and all individual parameters
        //     -> We use the parameters array to define the account

        $validated = $request->validated();

        $twofaccount = Arr::has($validated, 'uri')
            ? $this->twofaccountService->createFromUri($validated['uri'])
            : $this->twofaccountService->createFromParameters($validated);

        // Possible group association
        $this->groupService->assign($twofaccount->id);

        return (new TwoFAccountReadResource($twofaccount->refresh()))
                ->response()
                ->setStatusCode(201);
    }



    /**
     * Update a 2FA account
     *
     * @param  \App\Api\v1\Requests\TwoFAccountUpdateRequest  $request
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TwoFAccountUpdateRequest $request, TwoFAccount $twofaccount)
    {
        $validated = $request->validated();

        $this->twofaccountService->update($twofaccount, $validated);

        return (new TwoFAccountReadResource($twofaccount))
                ->response()
                ->setStatusCode(200);

    }


    /**
     * Save 2FA accounts order
     *
     * @param  \App\Api\v1\Requests\TwoFAccountReorderRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(TwoFAccountReorderRequest $request)
    {
        $validated = $request->validated();

        TwoFAccount::setNewOrder($validated['orderedIds']);

        return response()->json(['message' => 'order saved'], 200);
    }


    /**
     * Preview account using an uri, without any db moves
     * 
     * @param  \App\Api\v1\Requests\TwoFAccountUriRequest  $request
     * @return \App\Api\v1\Resources\TwoFAccountStoreResource
     */
    public function preview(TwoFAccountUriRequest $request)
    {
        $twofaccount = $this->twofaccountService->createFromUri($request->uri, false);

        return new TwoFAccountStoreResource($twofaccount);
    }


    /**
     * Get a One-Time Password
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function otp(Request $request, $id = null)
    {
        $inputs = $request->all();

        // The request input is the ID of an existing account
        if ( $id ) {
            try {
                $otp = $this->twofaccountService->getOTP((int) $id);
            }
            catch (UndecipherableException $ex) {
                return response()->json([
                    'message' => __('errors.cannot_decipher_secret')
                ], 400);
            }
        }

        // The request input is an uri
        else if ( count($inputs) === 1 && $request->has('uri') ) {
            $validatedData = $request->validate((new TwoFAccountUriRequest)->rules());
            $otp = $this->twofaccountService->getOTP($validatedData['uri']);
        }
        
        // return bad request if uri is provided with any other input
        else if ( count($inputs) > 1 && $request->has('uri')) {
            return response()->json([
                'message' => 'bad request',
                'reason' => ['uri' => __('validation.single', ['attribute' => 'uri'])]
            ], 400);
        }

        // The request inputs should define an account
        else {
            $validatedData = $request->validate((new TwoFAccountStoreRequest)->rules());
            $otp = $this->twofaccountService->getOTP($validatedData);
        }

        return response()->json($otp, 200);
    }


    /**
     * A simple and light method to get the account count.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(Request $request)
    {
        return response()->json([ 'count' => TwoFAccount::count() ], 200);
    }


    /**
     * 
     * Withdraw one or more accounts from their group
     * 
     * @param \App\Api\v1\Requests\TwoFAccountBatchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(TwoFAccountBatchRequest $request)
    {       
        $validated = $request->validated();
        
        if ($this->tooManyIds($validated['ids'])) {
            return response()->json([
                'message' => 'bad request',
                'reason' => [__('errors.too_many_ids')]
            ], 400);
        }

        $this->twofaccountService->withdraw($validated['ids']);
        
        return response()->json([ 'message' => 'accounts withdrawn' ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(TwoFAccount $twofaccount)
    {
        $this->twofaccountService->delete($twofaccount->id);

        return response()->json(null, 204);
    }


    /**
     * Remove the specified resources from storage.
     *
     * @param  \App\Api\v1\Requests\TwoFAccountBatchRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchDestroy(TwoFAccountBatchRequest $request)
    {
        $validated = $request->validated();

        if ($this->tooManyIds($validated['ids'])) {
            return response()->json([
                'message' => 'bad request',
                'reason' => [__('errors.too_many_ids')]
            ], 400);
        }

        $this->twofaccountService->delete($validated['ids']);

        return response()->json(null, 204);
    }


    /**
     * Checks ids length
     * 
     * @param string $ids comma-separated ids
     * @return bool whether or not the number of ids is acceptable
     */
    private function tooManyIds(string $ids) : bool
    {
        $arIds = explode(',', $ids, 100);
        $nb = count($arIds);

        return $nb > 99 ? true : false;
    }

}
