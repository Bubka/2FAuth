<?php

namespace App\Http\Controllers;

use App\TwoFAccount;
use App\Classes\TimedTOTP;
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
    public function show($id)
    {
        try {
            $twofaccount = TwoFAccount::FindOrFail($id);
            return response()->json($twofaccount, 200);
        } catch (\Exception $e) {
            return response()->json( ['message' => 'not found' ], 404);
        }
    }


    /**
     * Generate a TOTP
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function generateTOTP(TwoFAccount $twofaccount)
    {

        return response()->json(TimedTOTP::get($twofaccount->uri), 200);

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

        try {

            $twofaccount = TwoFAccount::FindOrFail($id);
            $twofaccount->update($request->all());

            return response()->json($twofaccount, 200);

        }
        catch (\Exception $e) {

            return response()->json( ['message' => 'not found' ] , 404);

        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $twofaccount = TwoFAccount::FindOrFail($id);

            // delete icon
            $storedIcon = 'public/icons/' . $twofaccount->icon;

            if( Storage::exists($storedIcon) ) {
                Storage::delete($storedIcon);
            }

            $twofaccount->delete();

            return response()->json(null, 204);

        }
        catch (\Exception $e) {

            return response()->json(['message' => 'already gone'], 404);

        }
    }

}
