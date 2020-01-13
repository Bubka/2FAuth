<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * log a user in
     * @return [type] [description]
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $credentials = [
            'email' => request('email'),
            'password' => request('password')
        ];

        if (Auth::attempt($credentials)) {
            $success['token'] = Auth::user()->createToken('MyApp')->accessToken;
            $success['name'] = Auth::user()->name;

            return response()->json(['success' => $success], 200);
        }

        return response()->json(['error' => 'Unauthorised'], 401);
    }


    /**
     * log out current user
     * @param  Request $request
     * @return json
     */
    public function logout()
    {
        $accessToken = Auth::user()->token();
        $accessToken->revoke();

        return response()->json(['success' => 'signed out']);
    }


    /**
     * check if a user exists
     * @param  Request $request [description]
     * @return json
     */
    public function checkUser()
    {

        $count = DB::table('users')->count();

        return response()->json(['userCount' => $count], 200);
    }


    /**
     * register new user
     * @param  Request $request [description]
     * @return json
     */
    public function register(Request $request)
    {

        // check if a user already exists
        $count = DB::table('users')->count();

        if( $count > 0 ) {
            return response()->json(['error' => __('already_one_user_registered')], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success' => $success]);
    }


    /**
     * get detailed information about a user
     * @return [type] [description]
     */
    public function getDetails()
    {
        return response()->json(Auth::user(), 200);
    }
}