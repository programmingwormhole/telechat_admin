<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function register (Request $request)
    {
        $request->validate([
            'name' => 'required',
            'date_of_birth' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'profile_picture' => 'required|image',
        ]);

        $filePath = $request->profile_picture->store('/profile_picture', 'public');
        $profilePicture = Storage::url($filePath);

        $user = User::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'profile_picture' => $profilePicture,
        ]);

        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'message' => 'User registered',
            'data' => $user,
            'token' => $token,
        ]);
    }

    public function login (Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            $user = User::where('email', $request->email)->first();

            $token = $user->createToken('auth')->plainTextToken;

            return response()->json([
                'message' => 'User Logged In',
                'data' => $user,
                'token' => $token,
            ]);

        } else {
            return response()->json([
                'message' => 'Invalid details',
            ], 400);
        }

    }
}
