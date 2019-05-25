<?php

namespace App\Http\Controllers;

use App\TwoFAccount;
use Illuminate\Http\Request;

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
