<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message'=> $validator->errors(),
            ], 400);
        };

        $user = $request->all();

        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $profilePicture = $request->file('profile_picture');

            $profilePictureName = time().'_'.$profilePicture->getClientOriginalName();
            
            $profilePicture->move(public_path('storage/profile'), $profilePictureName);
        }

        $user['profile_picture'] = 'storage/profile'.$profilePictureName;

        User::create($user);
        
        return response()->json([
            'status' => 'success',
            'message'=> 'User is created successfully'
        ], 201) ;
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message'=> $validator->errors(),
            ], 400);
        };

        $credential = [
            'email'=> $request->email,
            'password'=> $request->password,
        ];

        if (Auth::attempt($credential)) {
            $token = $request->user()->createToken('BlogPost')->plainTextToken;

            $response = [
                'email' => $request->email,
                'name' => $request->user()->name,
                'token' => $token
            ];

            return response()->json([
                'status' => 'success',
                'message'=> 'Logged in successfully',
                'data' => $response
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message'=> 'Failed Authentication',
            ], 400);
        }
    }

    public function profile() {
        $user = Auth::user();

        return response()->json([
            'status'=> 'success',
            'data'=> $user,
        ]);

    }

    public function logout() {
        Auth::user()->tokens()->delete();

        return response()->json([
            'status'=> 'success',
            'message'=> 'Logged out successfully'
        ]);
    }
}
