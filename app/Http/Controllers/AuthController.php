<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function login(Request $request){

        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => 'Incorrect email or password',
            ]);
        }
    
        $token = $user->createToken($user->email)->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ],200);
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required',
        ]);

        $registerData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];

        User::create($registerData);

        return Response([
            'message' => 'User Has Been Registered'
        ],201);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete(); 

        return Response([
            'message' => 'Logged Out'
        ],201);
    }
}
