<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // reg new user
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('brigade_token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // log
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // check email
        $user = User::where('email', $fields['email'])->first();

        //verify passcode
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => ' email or password incorrect'], 401);
        }

        $token = $user->createToken('brigade_token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    // logOut
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => ' LogOut with success']);
    }
}