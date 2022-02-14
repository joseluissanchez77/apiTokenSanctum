<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use App\Traits\RestResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use RestResponse;
    public function register(RegisterFormRequest $request)
    {

      /*   $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:0',
        ]);



        if ($validator->fails()) {
            return response()->json($validator->errors());
        } */

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'acces_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }


    public function login(LoginFormRequest $request, AuthenticationException $exception)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 401);
        }

        $user = User::where([
            ['email' ,$request->email]
        ])->firstOrFail();


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Hola'.$user->name,
            'accessToken' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Session cerrada correctament'
        ]);
    }
}
