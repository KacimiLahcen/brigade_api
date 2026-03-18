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
            'name' => 'required|string|max:100',
            'email' => 'required|string|unique:users | email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,client',
            'dietary_tags' => 'nullable|array'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role' => $fields['role'],
            'dietary_tags' => $fields['dietary_tags'] ?? [], // nullable
        ]);

        $token = $user->createToken('brigade_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // log
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required'
        ]);

        // check email
        $user = User::where('email', $request['email'])->first();

        //verify passcode
        if(!$user || !Hash::check($request['password'], $user->password)) {
            return response(['message' => ' email or password incorrect'], 401);
        }

        $token = $user->createToken('brigade_token')->plainTextToken;

        return response([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    // logOut
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => ' LogOut with success']);
    }

    // to get access to ur profile
    public function me(Request $request) {
        return response()->json($request->user());
    }
}