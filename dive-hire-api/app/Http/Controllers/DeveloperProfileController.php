<?php

namespace App\Http\Controllers;

use App\Models\DeveloperProfile;
use App\Models\Skill;
use Illuminate\Http\Request;

class DeveloperProfileController extends Controller
{

    private function resolveSkills(array $skills): array
    {
        $skillIds = [];
        foreach ($skills as $skillName) {
            $skill = Skill::firstOrCreate([
                'name' => ucfirst(strtolower(trim($skillName)))
            ]);
            $skillIds[] = $skill->id;
        }
        return $skillIds;
    }

    public function store(Request $request)
    {
        // return $request->all();
        $user = auth()->user();
        $test = null;


        if ($user->role !== 'developer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            // Basic profile
            'avatar'           => 'nullable|image',
            'experience_years' => 'nullable|integer',
            'location'         => 'nullable|string',
            'portfolio_link'   => 'nullable|url',
            'phone'            => 'nullable|string',
            'cv'               => 'nullable|file|mimes:pdf,doc,docx|max:2048',

            // After ✅
            'skills'   => 'nullable|array',
            'skills.*' => 'string|max:50',

            // Experiences
            'experiences'               => 'nullable|array',
            'experiences.*.company'     => 'required|string',        // ✅ just required
            'experiences.*.role'        => 'required|string',        // ✅
            'experiences.*.location'    => 'nullable|string',
            'experiences.*.start_date'  => 'required|date',          // ✅
            'experiences.*.end_date'    => 'nullable|date',
            'experiences.*.description' => 'nullable|string',

            // Projects
            'projects'               => 'nullable|array',
            'projects.*.title'       => 'required|string',           // ✅
            'projects.*.description' => 'nullable|string',
            'projects.*.url'         => 'nullable|url',
            'projects.*.image'       => 'nullable|image',
            'projects.*.skills'      => 'nullable|array',
            'projects.*.skills.*'    => 'string|max:50',
        ]);

        // 1. Handle avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // 2. Handle cv upload
        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('cvs', 'private');
        }

        $data['user_id'] = $user->id;

        // 3. Create or update basic profile
        $profile = DeveloperProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        // 4. Sync skills
        if ($request->has('skills')) {
            $profile->skills()->sync($this->resolveSkills($request->skills));
        }

        // 5. Handle experiences
        if ($request->has('experiences')) {
            // delete old ones first then recreate
            $profile->experiences()->delete();

            foreach ($request->experiences as $experience) {
                $profile->experiences()->create($experience);
            }
        }

        // 6. Handle projects
        if ($request->has('projects')) {
            // delete old ones first then recreate
            $profile->projects()->delete();

            foreach ($request->projects as $index => $projectData) {

                // handle project image upload
                $imagePath = null;
                if ($request->hasFile("projects.{$index}.image")) {

                    $imagePath = $request->file("projects.{$index}.image")->store('projects', 'public');
                    $test = $imagePath;
                }

                // create the project
                $project = $profile->projects()->create([
                    'title'       => $projectData['title'],
                    'description' => $projectData['description'] ?? null,
                    'url'         => $projectData['url'] ?? null,
                    'image'       => $imagePath,
                ]);

                // attach project skills
                if (!empty($projectData['skills'])) {
                    $project->skills()->sync($this->resolveSkills($projectData['skills']));
                }
            }
        }

        // return profile with all relations
        return response()->json(
            $profile->load(['skills', 'experiences', 'projects.skills'])
        );
    }

    public function show()
    {
        $user = auth()->user();

        if ($user->role !== 'developer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $profile = $user->developerProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        $profile->load(['skills', 'experiences', 'projects.skills']);
        
        return response()->json($profile, 200);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'developer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'avatar' => 'nullable|image',
            'experience_years' => 'nullable|integer',
            'location' => 'nullable|string',
            'portfolio_link' => 'nullable|url',
            'cv_path' => 'nullable|string',
        ]);

        $profile = $user->developerProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $profile->update($data);

        return response()->json($profile);
    }

    public function destroy(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'developer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $profile = $user->developerProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $profile->delete();

        return response()->json(['message' => 'Profile deleted successfully']);
    }

    public function myApplications()
    {
        $user = auth()->user();

        if ($user->role !== 'developer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $profile = $user->developerProfile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $applications = $user->develoeprApplications()->with('listing')->get();

        return response()->json($applications);
    }
}
