<?php

namespace App\Http\Controllers;

use App\Group;
use App\TwoFAccount;
use App\Classes\Options;
use App\Http\Requests\TwoFAccountReorderRequest;
use App\Http\Requests\TwoFAccountStoreRequest;
use App\Http\Requests\TwoFAccountUpdateRequest;
use App\Http\Resources\TwoFAccountReadResource;
use App\Http\Resources\TwoFAccountStoreResource;
use App\Http\Requests\TwoFAccountDeleteRequest;
use App\Http\Requests\TwoFAccountUriRequest;
use App\Http\Requests\TwoFAccountDynamicRequest;
use App\Services\TwoFAccountService;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class TwoFAccountController extends Controller
{
    /**
     * The TwoFAccount Service instance.
     */
    protected $twofaccountService;


    /**
     * Create a new controller instance.
     *
     * @param  TwoFAccountService  $twofaccountService
     * @return void
     */
    public function __construct(TwoFAccountService $twofaccountService)
    {
        $this->twofaccountService = $twofaccountService;
    }


    /**
     * List all resources
     *
     * @return \App\Http\Resources\TwoFAccountReadResource
     */
    public function index(Request $request)
    {
        $request->merge(['hideSecret' => true]);

        return TwoFAccountReadResource::collection(TwoFAccount::ordered()->get());
    }


    /**
     * Display a resource
     *
     * @param  \App\TwoFAccount  $twofaccount
     * 
     * @return \App\Http\Resources\TwoFAccountReadResource
     */
    public function show(TwoFAccount $twofaccount)
    {
        return new TwoFAccountReadResource($twofaccount);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TwoFAccountDynamicRequest  $request
     * 
     * @return \App\Http\Resources\TwoFAccountReadResource
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
        $groupId = Options::get('defaultGroup') === '-1' ? (int) Options::get('activeGroup') : (int) Options::get('defaultGroup');
        
        // 0 is the pseudo group 'All', only groups with id > 0 are true user groups
        if( $groupId > 0 ) {
            $group = Group::find($groupId);

            if($group) {
                $group->twofaccounts()->save($twofaccount);
            }
        }

        return (new TwoFAccountReadResource($twofaccount))
                ->response()
                ->setStatusCode(201);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\TwoFAccountUpdateRequest  $request
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function update(TwoFAccountUpdateRequest $request, TwoFAccount $twofaccount)
    {
        $validated = $request->validated();

        $this->twofaccountService->update($twofaccount, $validated);

        return response()->json($twofaccount, 200);

    }


    /**
     * Set new order.
     *
     * @param  App\Http\Requests\TwoFAccountReorderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function reorder(TwoFAccountReorderRequest $request)
    {
        $validated = $request->validated();

        $this->twofaccountService->saveOrder($validated['orderedIds']);

        return response()->json(['message' => 'order saved'], 200);
    }


    /**
     * Preview account using an uri, without any db moves
     * 
     * @param  \App\Http\Requests\TwoFAccountUriRequest  $request
     * 
     * @return \App\Http\Resources\TwoFAccountStoreResource
     */
    public function preview(TwoFAccountUriRequest $request)
    {
        $twofaccount = $this->twofaccountService->createFromUri($request->uri, $saveToDB = false);

        return new TwoFAccountStoreResource($twofaccount);
    }


    /**
     * Get a One-Time Password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function otp(Request $request, $id = null)
    {
        $inputs = $request->all();

        // The request input is the ID of an existing account
        if ( $id ) {
            $otp = $this->twofaccountService->getOTP((int) $id);
        }

        // The request input is an uri
        else if ( count($inputs) === 1 && $request->has('uri') ) {
            $validatedData = $request->validate((new TwoFAccountUriRequest)->rules());
            $otp = $this->twofaccountService->getOTP($validatedData['uri']);
        }
        
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
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function count(Request $request)
    {
        return response()->json([ 'count' => TwoFAccount::count() ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(TwoFAccount $twofaccount)
    {
        $this->twofaccountService->delete($twofaccount->id);

        return response()->json(null, 204);
    }


    /**
     * Remove the specified resources from storage.
     *
     * @param  \App\Http\Requests\TwoFAccountDeleteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function batchDestroy(TwoFAccountDeleteRequest $request)
    {
        $this->twofaccountService->delete($request->ids);

        return response()->json(null, 204);
    }

}
