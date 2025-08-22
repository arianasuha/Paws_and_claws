<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\ReportLostPet;
use App\Http\Requests\Pet\ReportLostPetRegisterRequest;
use App\Http\Requests\Pet\ReportLostPetUpdateRequest;
use Illuminate\Http\JsonResponse;

class ReportLostPetController extends Controller
{

    public function index(): JsonResponse
    {
        // Retrieve all lost pet reports with associated user and pet details.
        $reports = ReportLostPet::with(['user', 'pet'])->get();

        return response()->json($reports);
    }

    public function store(ReportLostPetRegisterRequest $request): JsonResponse
    {
        // Get the authenticated user's ID
        $userId = $request->user()->id;

        // Create a new lost pet report using the validated data and the user ID.
        $report = ReportLostPet::create([
            'user_id' => $userId,
            'location' => $request->location,
            'date_lost' => $request->date_lost,
            'pet_id' => $request->pet_id,
            'status' => $request->status,
        ]);

        // Load the relationships for the response.
        $report->load(['user', 'pet']);

        return response()->json($report, 201);
    }


    public function show(ReportLostPet $reportLostPet): JsonResponse
    {
        // Load the relationships for the response.
        $reportLostPet->load(['user', 'pet']);

        return response()->json($reportLostPet);
    }


    public function update(ReportLostPetUpdateRequest $request, ReportLostPet $reportLostPet): JsonResponse
    {
        // Authorization check to ensure only the owner can update the report.
        if ($request->user()->id !== $reportLostPet->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update the report with the validated data.
        $reportLostPet->update($request->validated());

        // Load the relationships for the response.
        $reportLostPet->load(['user', 'pet']);

        return response()->json($reportLostPet);
    }


    public function destroy(ReportLostPet $reportLostPet): JsonResponse
    {
        // Authorization check to ensure only the owner can delete the report.
        if (request()->user()->id !== $reportLostPet->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete the report.
        $reportLostPet->delete();

        return response()->json(null, 204);
    }
}
