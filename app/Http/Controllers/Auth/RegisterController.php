<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Settings;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Handle a registration request for the application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserStoreRequest $request)
    {
        if (Settings::get('disableRegistration') == true) {
            return response()->json(['message' => 'forbidden'], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();

        event(new Registered($user = $this->create($validated)));

        $this->guard()->login($user);

        return response()->json([
            'message' => 'account created',
            'name'    => $user->name,
        ], 201);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Log::info(sprintf('User ID #%s created', $user->id));

        if (User::count() == 1) {
            $user->is_admin = true;
            $user->save();
            Log::notice(sprintf('User ID #%s set as administrator', $user->id));
        }

        return $user;
    }
}
