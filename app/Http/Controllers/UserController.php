<?php

namespace App\Http\Controllers;

use App\User;
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

        $this->validate($request, [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => request('email'),
            'password' => request('password')
        ];

        if (Auth::attempt($credentials)) {
            $success['token'] = Auth::user()->createToken('MyApp')->accessToken;
            $success['name'] = Auth::user()->name;

            return response()->json(['message' => $success], 200);
        }

        return response()->json(['message' => 'unauthorised'], 401);
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

        return response()->json(['message' => 'signed out']);
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
            return response()->json(['message' => __('errors.already_one_user_registered')], 400);
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['message' => $success]);
    }
}