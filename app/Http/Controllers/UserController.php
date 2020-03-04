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