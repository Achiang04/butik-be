<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

    public function logout(Request $request){
        $request->user()->tokens()->delete(); 

        return Response([
            'message' => 'Logged Out'
        ],201);
    }
}
