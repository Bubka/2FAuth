<?php

namespace App\Http\Controllers;

use App\TwoFAccount;
use App\Classes\OTP;
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
        return response()->json(TwoFAccount::all()->toArray());
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
            'service' => 'required',
            'uri' => 'required|regex:/^otpauth:\/\/[h,t]otp\//i',
        ]);

        OTP::get($request->uri);

        $twofaccount = TwoFAccount::create([
            'service' => $request->service,
            'account' => $request->account,
            'uri' => $request->uri,
            'icon' => $request->icon
        ]);

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
     * Generate a TOTP
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function generateOTP(TwoFAccount $twofaccount)
    {
        return response()->json(OTP::generate($twofaccount->uri), 200);
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
        

        if( $twofaccount->type === 'hotp' ) {

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
