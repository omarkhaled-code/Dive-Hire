<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\EmployerProfile;
use Illuminate\Http\Request;



class EmployerProfileController extends Controller
{
    public function store(Request $request)
    {

        // #[Fillable(['user_id', 'phone', 'avatar', 'company_name', 'company_logo', 'company_website', 'company_size', 'company_location', 'company_bio'])]
        // Implement logic to create or update employer profile
        $user = auth()->user();

        if ($user->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $data = $request->validate([
            'phone' => 'nullable|string',
            'avatar' => 'nullable|image',
            'company_name' => 'nullable|string',
            'company_logo' => 'nullable|image',
            'company_website' => 'nullable|url',
            'company_size' => 'nullable|string',
            'company_location' => 'nullable|string',
            'company_bio' => 'nullable|string',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
        $data['avatar'] = $avatarPath;

        $companyLogoPath = null;
        if ($request->hasFile('company_logo')) {
            $companyLogoPath = $request->file('company_logo')->store('company_logos', 'public');
        }
        $data['company_logo'] = $companyLogoPath;
        $data['user_id'] = $user->id;

        $profile = EmployerProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
        return response()->json($profile);
    }

    public function show()
    {
        // Implement logic to retrieve employer profile
        $user = auth()->user();

        if ($user->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $profile = $user->employerProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $profile->load('user.listings'); // Load related listings
        return response()->json($profile, 200);
    }

    public function update(Request $request)
    {
        // Implement logic to update employer profile
        $user = auth()->user();
        if ($user->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'phone' => 'nullable|string',
            'avatar' => 'nullable|image',
            'company_name' => 'nullable|string',
            'company_logo' => 'nullable|image',
            'company_website' => 'nullable|url',
            'company_size' => 'nullable|string',
            'company_location' => 'nullable|string',
            'company_bio' => 'nullable|string',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
        $data['avatar'] = $avatarPath;

        $companyLogoPath = null;
        if ($request->hasFile('company_logo')) {
            $companyLogoPath = $request->file('company_logo')->store('company_logos', 'public');
        }
        $data['company_logo'] = $companyLogoPath;
        $data['user_id'] = $user->id;
        $profile = EmployerProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
        return response()->json($profile);
    }

    public function destroy()
    {
        // Implement logic to delete employer profile
        $user = auth()->user();

        if ($user->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $profile = $user->employerProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $profile->delete();

        return response()->json(['message' => 'Profile deleted successfully']);
    }

    public function mYListings()
    {
        $user = auth()->user();

        if ($user->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $listings = $user->listings()->with('skills')->get();

        return response()->json($listings);
    }


    public function myApplications()
    {
        $user = auth()->user();

        if ($user->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $applications = $user->listings()->with('applications.developer')->get()->pluck('applications')->flatten();

        return response()->json($applications);
    }



}
