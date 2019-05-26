<?php

namespace App\Http\Controllers;

use App\TwoFAccount;
use OTPHP\TOTP;
use OTPHP\Factory;
use Illuminate\Http\Request;
use ParagonIE\ConstantTime\Base32;

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
        $twofaccount = TwoFAccount::create([
            'name' => $request->name,
            'secret' => $request->secret
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
    public function generateTOTP(TwoFAccount $twofaccount)
    {
        try {
            $otp = Factory::loadFromProvisioningUri($twofaccount->secret);
        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'message' => 'Error generating TOTP',
            ], 500);
        }

        return response()->json([
                'totp' => $otp->now(),
            ], 200);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TwoFAccount $twofaccount)
    {
        $twofaccount->update($request->all());

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
     * Remove the specified soft deleted resource from storage.
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($id)
    {
        $twofaccount = TwoFAccount::onlyTrashed()->findOrFail($id);
        $twofaccount->forceDelete();

        return response()->json(null, 204);
    }

}
