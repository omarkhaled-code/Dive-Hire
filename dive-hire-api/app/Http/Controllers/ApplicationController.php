<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    // ['developer_id', 'listing_id', 'cover_letter', 'status']

    public function apply(Request $request, string $listingId)
    {
        $data = $request->validate([
            'cover_letter' => 'sometimes|string',
        ]);
        $data['developer_id'] = auth()->id();
        $data['listing_id'] = $listingId;
        $data['status'] = 'pending';

        if (auth()->user()->role !== 'developer' || !auth()->user()->hasProfile()) {
            return response()->json(['message' => 'Only developers can apply for listings.'], 403);
        }
        if ($data['status'] !== 'pending') {
            return response()->json(['message' => 'Status must be pending when applying.'], 400);
        }

        if (Application::where('developer_id', auth()->id())->where('listing_id', $data['listing_id'])->exists()) {
            return response()->json(['message' => 'You have already applied for this listing.'], 400);
        }

        try {
            $application = Application::create($data);
            return response()->json($application, 201);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Invalid data. The listing or developer does not exist.'
            ], 400);
        }
    }

    public function update(Request $request, string $id)
    {
        $application = Application::findOrFail($id);
        $data = $request->validate([
            'status' => 'required|string|in:pending,interview,done',
        ]);
        $employerId = $application->listing->employer_id;

        if (auth()->user()->role !== 'employer' || auth()->id() !== $employerId) {
            return response()->json(['message' => 'Only the employer who owns the listing can update the application status.'], 403);
        }

        $application->update($data);

        return response()->json($application);
    }

    public function destroy(string $id)
    {
        $application = Application::findOrFail($id);
        $employerId = $application->listing->employer_id;

        if (auth()->user()->role !== 'employer' || auth()->id() !== $employerId) {
            return response()->json(['message' => 'Only the employer who owns the listing can delete the application.'], 403);
        }

        $application->delete();

        return response()->json(['message' => 'Application deleted successfully.']);
    }

    public function show(string $applicationId)
    {
        $application = Application::findOrFail($applicationId);
        $employerId = $application->listing->employer_id;

        if (auth()->user()->role !== 'employer' || auth()->id() !== $employerId) {
            return response()->json(['message' => 'Only the employer who owns the listing can view the application.'], 403);
        }
        $application->load('developer', 'listing');
        return response()->json($application);
    }
}
