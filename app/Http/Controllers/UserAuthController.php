<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import User model
use Illuminate\Support\Facades\Hash; // Import Hash facade

class UserAuthController extends Controller
{
    public function register(Request $request){
        $registerUserData = $request->validate([
            'username'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|min:8',
            'phone_number'=>'nullable|string', // Added phone_number field
            'address'=>'nullable|string', // Added address field
        ]);
    
        // Create a new user with the provided data
        User::create([
            'username' => $registerUserData['username'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
            'phone_number' => $registerUserData['phone_number'], // Assign phone_number field
            'address' => $registerUserData['address'], // Assign address field
        ]);
    
        return response()->json([
            'message' => 'User Created ',
        ]);
    }
    
    
    

    public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function logout(Request $request){
        if ($request->user()) {
            $request->user()->tokens()->delete();
            return response()->json([
                "message" => "Logged out successfully"
            ]);
        } else {
            return response()->json([
                "message" => "No user authenticated"
            ], 401);
        }
    }
    
}
