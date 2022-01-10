<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        return $user->createToken($user->email)->plainTextToken;
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
        ],200);
    }

    

}
