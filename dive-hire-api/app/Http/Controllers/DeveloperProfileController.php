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
        $user = auth()->user();

        if (!$user || $user->role !== 'developer') {

            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $data = $request->validate([

            /*
        |--------------------------------------------------------------------------
        | Basic Profile
        |--------------------------------------------------------------------------
        */

            'avatar'            => 'nullable|image|max:2048',
            'experience_years'  => 'nullable|integer|min:0',
            'location'          => 'nullable|string|max:255',
            'portfolio_link'    => 'nullable|url',
            'phone'             => 'nullable|string|max:30',
            'job_title'         => 'nullable|string|max:255',
            'bio'               => 'nullable|string',
            'ready_for_work'    => 'nullable|string|max:255',

            'cv'                => 'nullable|file|mimes:pdf,doc,docx|max:4096',

            /*
        |--------------------------------------------------------------------------
        | Skills
        |--------------------------------------------------------------------------
        */

            'skills'            => 'nullable|array',
            'skills.*'          => 'string|max:50',

            /*
        |--------------------------------------------------------------------------
        | Experiences
        |--------------------------------------------------------------------------
        */

            'experience'                        => 'nullable|array',

            'experience.*.company_name'         => 'required|string|max:255',

            'experience.*.job_title'            => 'required|string|max:255',

            'experience.*.start_date'           => 'required|date',

            'experience.*.end_date'             => 'nullable|date|after_or_equal:experience.*.start_date',

            'experience.*.achievements'         => 'nullable|array',

            'experience.*.achievements.*'       => 'nullable|string',

            /*
        |--------------------------------------------------------------------------
        | Projects
        |--------------------------------------------------------------------------
        */

            'project'                           => 'nullable|array',

            'project.*.title'                  => 'required|string|max:255',

            'project.*.description'            => 'nullable|string',

            'project.*.projectLink'            => 'nullable|url',

            'project.*.thumbnail'              => 'nullable|image|max:4096',

            'project.*.techStack'              => 'nullable|array',

            'project.*.techStack.*'            => 'nullable|string|max:50',

        ]);

        /*
    |--------------------------------------------------------------------------
    | Upload Avatar
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('avatar')) {

            $data['avatar'] = $request
                ->file('avatar')
                ->store('avatars', 'public');
        }

        /*
    |--------------------------------------------------------------------------
    | Upload CV
    |--------------------------------------------------------------------------
    */

        if ($request->hasFile('cv')) {

            $data['cv_path'] = $request
                ->file('cv')
                ->store('cvs', 'private');
        }

        $data['user_id'] = $user->id;

        /*
    |--------------------------------------------------------------------------
    | Create / Update Profile
    |--------------------------------------------------------------------------
    */

        $profile = DeveloperProfile::updateOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'avatar'            => $data['avatar'] ?? null,
                'experience_years'  => $data['experience_years'] ?? null,
                'location'          => $data['location'] ?? null,
                'portfolio_link'    => $data['portfolio_link'] ?? null,
                'phone'             => $data['phone'] ?? null,
                'job_title'         => $data['job_title'] ?? null,
                'bio'               => $data['bio'] ?? null,
                'ready_for_work'    => $data['ready_for_work'] ?? null,
                'cv_path'           => $data['cv_path'] ?? null,
            ]
        );

        /*
    |--------------------------------------------------------------------------
    | Sync Skills
    |--------------------------------------------------------------------------
    */

        if (!empty($data['skills'])) {

            $profile->skills()->sync(
                $this->resolveSkills($data['skills'])
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Experiences
    |--------------------------------------------------------------------------
    */

        if (!empty($data['experience'])) {

            $profile->experiences()->delete();

            foreach ($data['experience'] as $experience) {

                $profile->experiences()->create([

                    'company_name' => $experience['company_name'],

                    'job_title'    => $experience['job_title'],

                    'start_date'   => $experience['start_date'],

                    'end_date'     => $experience['end_date'] ?? null,

                    'achievements' => json_encode(
                        $experience['achievements'] ?? []
                    ),

                ]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Projects
    |--------------------------------------------------------------------------
    */

        if (!empty($data['project'])) {

            $profile->projects()->delete();

            foreach ($data['project'] as $index => $projectData) {

                $thumbnailPath = null;

                if ($request->hasFile("project.{$index}.thumbnail")) {

                    $thumbnailPath = $request
                        ->file("project.{$index}.thumbnail")
                        ->store('projects', 'public');
                }

                $project = $profile->projects()->create([

                    'title'       => $projectData['title'],

                    'description' => $projectData['description'] ?? null,

                    'url'         => $projectData['projectLink'] ?? null,

                    'image'       => $thumbnailPath,

                ]);

                if (!empty($projectData['techStack'])) {

                    $project->skills()->sync(
                        $this->resolveSkills($projectData['techStack'])
                    );
                }
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

        return response()->json([

            'message' => 'Profile saved successfully',

            'profile' => $profile->load([
                'skills',
                'experiences',
                'projects.skills'
            ])

        ], 200);
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
