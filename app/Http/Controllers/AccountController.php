<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Account::all()->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account = Account::create([
            'name' => $request->name,
            'secret' => $request->secret
        ]);

        $data = [
            'data' => $account,
            'status' => (bool) $account,
            'message' => $account ? 'Account Created' : 'Error Creating Account',
        ];

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(account $account)
    {
        return response()->json($account);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, account $account)
    {
        $status = $account->update($request->all());

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Account Updated' : 'Error Updating Account'
        ]);

        //return response()->json($request, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(account $account)
    {
        if($account->deleted_at == null){
          $status = $account->delete();
        }
        else {
          $status = $account->forceDelete();
        }

        return response()->json([
            'status' => $status,
            'message' => $status ? 'Account Deleted' : 'Error Deleting Account'
        ]);
    }

}
