<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserRequest $request) {
        $data = $request->validate($request->rules());

        $user = User::where('email', $data['email'])->first();

        if($user != null) {
            return [
                'message' => 'This email is already taken'
            ];
        }
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        $token = $user->createToken('user-access')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();

        if(!$user) {
            return [
                'message' => 'This user does not exist'
            ];
        }

        if(Hash::check($user->password, $data['password'])) {
            return [
                'message' => 'Wrong credentials'
            ];
        }

        $token = $user->createToken('user-access')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return [
            'message' => 'User logged out'
        ];
    }
}
