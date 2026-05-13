<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeveloperProfileController;
use App\Http\Controllers\EmployerProfileController;
use App\Http\Controllers\ListingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Group of routes that require authentication and prefix /v1
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Example route for getting user profile

    // Developer profile routes
    Route::post('/developer-profile', [DeveloperProfileController::class, 'store']);
    Route::get('/developer-profile', [DeveloperProfileController::class, 'show']);
    Route::put('/developer-profile', [DeveloperProfileController::class, 'update']);
    Route::delete('/developer-profile', [DeveloperProfileController::class, 'destroy']);
    Route::get('/developer/applications', [DeveloperProfileController::class, 'myApplications']);

    // Employer profile routes
    Route::post('/employer-profile', [EmployerProfileController::class, 'store']);
    Route::get('/employer-profile', [EmployerProfileController::class, 'show']);
    Route::put('/employer-profile', [EmployerProfileController::class, 'update']);
    Route::delete('/employer-profile', [EmployerProfileController::class, 'destroy']);
    Route::get('/employer/applications', [EmployerProfileController::class, 'myApplications']);
    Route::get('/employer/listings', [EmployerProfileController::class, 'mYListings']);

    // Jobs routes
    Route::apiResource('listings', ListingController::class);
    // Route::apiResource('applications', ApplicationController::class);

    // Application routes
    Route::post('/listings/{listingId}/apply', [ApplicationController::class, 'apply']);
    Route::get('/applications/{applicationId}', [ApplicationController::class, 'show']);

    // user
    // Route::get('/me', [AuthController::class, 'user']);
    Route::get('/me', [AuthController::class, 'me']);

    // Add more authenticated routes here
});

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});
