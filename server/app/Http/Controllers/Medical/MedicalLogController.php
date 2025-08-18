<?php

namespace App\Http\Controllers\Medical;

use App\Http\Controllers\Controller;
use App\Models\MedicalLog;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\MedicalLog\MedicalLogRegisterRequest;
use App\Http\Requests\MedicalLog\MedicalLogUpdateRequest;

class MedicalLogController extends Controller
{
    /**
     * Display a listing of the medical logs.
     *
     * Retrieves all medical logs, eager-loading the associated appointment and pet details
     * to prevent N+1 query issues.
     *
     * @return JsonResponse Returns a JSON response containing a collection of MedicalLog resources.
     */
    public function index(): JsonResponse
    {
        // Retrieve all medical logs with associated appointment and pet details.
        $medicalLogs = MedicalLog::with(['appointment', 'pets'])->get();

        return response()->json($medicalLogs);
    }

    /**
     * Store a newly created medical log in storage.
     *
     * Creates a new MedicalLog record using validated data from the request. It
     * returns the newly created resource.
     *
     * @param MedicalLogRegisterRequest $request The request object containing validated data for the new medical log.
     * @return JsonResponse Returns a JSON response with the newly created MedicalLog resource and a 201 Created status code.
     */
    public function store(MedicalLogRegisterRequest $request): JsonResponse
    {
        // Create a new medical log using the validated data.
        $medicalLog = MedicalLog::create($request->validated());

        // Load the relationships for the response.
        $medicalLog->load(['appointment', 'pets']);

        return response()->json($medicalLog, 201);
    }

    /**
     * Display the specified medical log.
     *
     * Retrieves and returns a single MedicalLog resource, including its
     * associated appointment and pet details.
     *
     * @param MedicalLog $medicalLog The MedicalLog instance resolved via route-model binding.
     * @return JsonResponse Returns a JSON response with the specified MedicalLog resource.
     */
    public function show(MedicalLog $medicalLog): JsonResponse
    {
        // Load the relationships for the response.
        $medicalLog->load(['appointment', 'pets']);

        return response()->json($medicalLog);
    }

    /**
     * Update the specified medical log in storage.
     *
     * Updates an existing MedicalLog record with validated data.
     *
     * @param MedicalLogUpdateRequest $request The request object containing validated data for the update.
     * @param MedicalLog $medicalLog The MedicalLog instance resolved via route-model binding.
     * @return JsonResponse Returns a JSON response with the updated MedicalLog resource.
     */
    public function update(MedicalLogUpdateRequest $request, MedicalLog $medicalLog): JsonResponse
    {
        // Update the medical log with the validated data.
        $medicalLog->update($request->validated());

        // Load the relationships for the response.
        $medicalLog->load(['appointment', 'pets']);

        return response()->json($medicalLog);
    }

    /**
     * Remove the specified medical log from storage.
     *
     * Deletes a specific MedicalLog record.
     *
     * @param MedicalLog $medicalLog The MedicalLog instance resolved via route-model binding.
     * @return JsonResponse Returns a JSON response with a null body and a 204 No Content status code upon successful deletion.
     */
    public function destroy(MedicalLog $medicalLog): JsonResponse
    {
        // Delete the medical log.
        $medicalLog->delete();

        return response()->json(null, 204);
    }
}
