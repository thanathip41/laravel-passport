<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:191',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'code' => 400
            ],400);
        }

        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'success' => true,
            'access_token' => $accessToken,
            'code' => 201
        ],201);
    }

    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($login)) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'code' => 400
            ],400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([
            'success' => true,
            'access_token' => $accessToken,
            'code' => 200
        ],200);
    }
    
    public function logout (Request $request) {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        return response()->json([],204);
    }
}