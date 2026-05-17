<?php

namespace App\Http\Controllers;

use App\Models\DeveloperProfile;
use App\Models\EmployerProfile;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{



    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();

        $relations = [];

        // if ($user->role === 'developer' && $user->hasProfile()) {
        //     // $relations = [
        //     //     'developerProfile.projects',
        //     //     'developerProfile.projects.skills',
        //     //     'developerProfile.skills',
        //     //     'developerProfile.experiences',
        //     //     'developerProfile.experiences.achievements',
        //     // ];
        //     $relations = [
        //         'developerProfile',
        //     ];
        // } elseif ($user->role === 'employer') {
        //     $relations = [
        //         'employerProfile',
        //     ];
        // }


        $token = $user->createToken('auth_token')->plainTextToken;

        $hasProfile = $user->hasProfile();

        return response()->json([
            'user' => $user,
            'token' => $token,
            'has_profile' => $hasProfile
        ]);
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:employer,developer',
            'password' => 'required|string|min:6'
        ]);

        $credentials['password'] = bcrypt($credentials['password']);

        $user = User::create($credentials);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me() {
        $user = auth()->user();
        
        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function user()
    {
        
        $user = auth()->user();

        $relations = [];

        if ($user->role === 'developer') {
            $relations = [
                'developerProfile.projects',
                'developerProfile.projects.skills',
                'developerProfile.skills',
                'developerProfile.experiences',
                'developerProfile.experiences.achievements',
            ];
        } elseif ($user->role === 'employer') {
            $relations = [
                'employerProfile',
            ];
        }


        $user->load($relations);

        return response()->json([
            'data' => $user

        ]);
    }
}
