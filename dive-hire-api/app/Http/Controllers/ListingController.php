<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListingController extends Controller
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

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'location' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'salary_min' => 'required|integer|min:0',
            'salary_max' => 'required|integer|min:0|gte:salary_min',
            'type' => 'required|string|in:full-time,part-time,freelance',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:255',
        ]);


        if (auth()->user()->role !== 'employer' || !auth()->user()->hasProfile()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $data['employer_id'] = auth()->id();


        DB::beginTransaction();

        try {
            $listing = Listing::create($data);

            if (!empty($data['skills'])) {
                $listing->skills()->sync($this->resolveSkills($data['skills']));
            }

            DB::commit();
            return response()->json($listing, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function show($id)
    {
        $listing = Listing::findOrFail($id);
        return response()->json($listing);
    }

    public function update(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);

        if (!$listing) {
            return response()->json(['error' => 'Listing not found'], 404);
        }

        if ($listing->employer_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:1000',
            'location' => 'sometimes|required|string|max:255',
            'experience_years' => 'sometimes|required|integer|min:0',
            'salary_min' => 'sometimes|required|integer|min:0',
            'salary_max' => 'sometimes|required|integer|min:0|gte:salary_min',
            'type' => 'sometimes|required|string|in:full-time,part-time,freelance,remote',
            'status' => 'sometimes|required|string|in:pending,interviewing,done',
            'skills' => 'sometimes|array',
            'skills.*' => 'string|max:255',
        ]);

        $listing->update($data);
        return response()->json($listing);
    }

    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);

        if ($listing->employer_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $listing->delete();
        return response()->json(null, 204);
    }
}
