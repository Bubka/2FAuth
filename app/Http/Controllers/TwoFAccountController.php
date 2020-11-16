<?php

namespace App\Http\Controllers;

use App\Group;
use App\TwoFAccount;
use App\Classes\OTP;
use App\Classes\Options;
use Illuminate\Http\Request;
use ParagonIE\ConstantTime\Base32;
use Illuminate\Support\Facades\Storage;

class TwoFAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(TwoFAccount::ofGroup(Options::get('activeGroup'))->ordered()->get()->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // see https://github.com/google/google-authenticator/wiki/Key-Uri-Format
        // for otpauth uri format validation

        $this->validate($request, [
            'service' => 'required|string',
            'account' => 'required_without:uri|nullable|string|regex:/^[^:]+$/i',
            'icon' => 'nullable|string',
            'uri' => 'nullable|string|regex:/^otpauth:\/\/[h,t]otp\//i',
            'otpType' => 'required_without:uri|in:totp,hotp,TOTP,HOTP',
            'secret' => 'required_without:uri|string',
            'digits' => 'nullable|integer|between:6,10',
            'algorithm' => 'nullable|in:sha1,sha256,sha512,md5',
            'totpPeriod' => 'nullable|integer|min:1',
            'hotpCounter' => 'nullable|integer|min:0',
            'imageLink' => 'nullable|url',
        ]);

        // Two possible cases :
        // - The most common case, the uri is provided thanks to a QR code live scan or file upload
        //     -> We use this uri to populate the account
        // - The advanced form has been used and provide no uri but all individual parameters
        //     -> We use the parameters collection to populate the account
        $twofaccount = new TwoFAccount;

        if( $request->uri ) {
            $twofaccount->populateFromUri($request->uri);
        }
        else {
            $twofaccount->populate($request->all());
        }

        $twofaccount->save();

        // Possible group association
        $groupId = Options::get('defaultGroup') === '-1' ? (int) Options::get('activeGroup') : (int) Options::get('defaultGroup');
        
        // 0 is the pseudo group 'All', only groups with id > 0 are true user groups
        if( $groupId > 0 ) {
            $group = Group::find($groupId);

            if($group) {
                $group->twofaccounts()->save($twofaccount);
            }
        }

        return response()->json($twofaccount, 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function show(TwoFAccount $twofaccount)
    {
        return response()->json($twofaccount, 200);
    }


    /**
     * Display the specified resource with all attributes.
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function showWithSensitive(TwoFAccount $twofaccount)
    {
        return response()->json($twofaccount->makeVisible(['uri', 'secret', 'algorithm']), 200);
    }


    /**
     * Save new order.
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request)
    {
        TwoFAccount::setNewOrder($request->orderedIds);
        return response()->json('order saved', 200);
    }


    /**
     * Generate a TOTP
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateOTP(Request $request)
    {

        if( $request->id ) {

            // The request data is the Id of an existing account
            $twofaccount = TwoFAccount::FindOrFail($request->id);
        }
        else if( $request->otp['uri'] ) {

            // The request data contain an uri
            $twofaccount = new TwoFAccount;
            $twofaccount->populateFromUri($request->otp['uri']);
        }
        else {

            // The request data should contain all otp parameter
            $twofaccount = new TwoFAccount;
            $twofaccount->populate($request->otp);
        }

        if( $twofaccount->otpType === 'hotp' ) {

            // returned counter & uri will be updated
            $twofaccount->increaseHotpCounter();

            // and the db too
            if( $request->id ) {
                $twofaccount->save();
            }
        }

        if( $request->id ) {
            return response()->json($twofaccount, 200);
        }

        return response()->json($twofaccount->makeVisible(['uri', 'secret', 'algorithm']), 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'service' => 'required',
        ]);


        // Here we catch a possible missing model exception in order to
        // delete orphan submited icon
        try {

            $twofaccount = TwoFAccount::FindOrFail($id);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            if( $request->icon ) {
                Storage::delete('public/icons/' . $request->icon);
            }
            
            throw $e;
        }
        
        if( $twofaccount->otpType === 'hotp' ) {

            // HOTP can be desynchronized from the verification
            // server so we let the user the possibility to force
            // the counter.

            $this->validate($request, [
                'counter' => 'required|integer',
            ]);

            // we set an OTP object to get the its current counter
            // and we update it if a new one has been submited
            $otp = OTP::get($twofaccount->uri);

            if( $otp->getCounter() !== $request->counter ) {
                $otp->setParameter( 'counter', $request->counter );
                $twofaccount->uri = $otp->getProvisioningUri();
            }
        }

        $twofaccount->update([
            'service' => $request->service,
            'account' => $request->account,
            'icon' => $request->icon,
            'uri' => $twofaccount->uri,
        ]);

        return response()->json($twofaccount, 200);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(TwoFAccount $twofaccount)
    {
        $twofaccount->delete();

        return response()->json(null, 204);
    }


    /**
     * Remove the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function batchDestroy(Request $request)
    {
        $ids = $request->all();
        
        TwoFAccount::destroy($ids);

        return response()->json(null, 204);
    }

}
