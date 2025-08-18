<?php

namespace App\Http\Controllers\Medical;

use App\Http\Controllers\Controller;
use App\Models\DiseaseLog;
use App\Http\Requests\Medical\DiseaseLogRegisterRequest;
use App\Http\Requests\Medical\DiseaseLogUpdateRequest;
use Illuminate\Http\JsonResponse;

class DiseaseLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieve all disease logs and eager load the 'pets' relationship
        // to avoid the N+1 query problem.
        $diseaseLogs = DiseaseLog::with('pets')->get();

        return response()->json($diseaseLogs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  DiseaseLogRegisterRequest  $request
     * @return JsonResponse
     */
    public function store(DiseaseLogRegisterRequest $request): JsonResponse
    {
        // The validated data is used to create a new DiseaseLog record.
        $diseaseLog = DiseaseLog::create($request->validated());

        // Assuming pet_ids are submitted in the request, we sync them.
        if ($request->has('pet_ids')) {
            $diseaseLog->pets()->sync($request->input('pet_ids'));
        }

        // Re-fetch the disease log with its pets relationship for the response.
        $diseaseLog->load('pets');

        return response()->json($diseaseLog, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  DiseaseLog  $diseaseLog
     * @return JsonResponse
     */
    public function show(DiseaseLog $diseaseLog): JsonResponse
    {
        // Eager load the 'pets' relationship for the specific disease log.
        $diseaseLog->load('pets');

        return response()->json($diseaseLog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  DiseaseLogUpdateRequest  $request
     * @param  DiseaseLog  $diseaseLog
     * @return JsonResponse
     */
    public function update(DiseaseLogUpdateRequest $request, DiseaseLog $diseaseLog): JsonResponse
    {
        // Update the disease log with the validated data from the request.
        $diseaseLog->update($request->validated());

        // Assuming pet_ids are submitted, we sync the relationship.
        if ($request->has('pet_ids')) {
            $diseaseLog->pets()->sync($request->input('pet_ids'));
        }

        // Re-fetch the disease log with its pets relationship for the response.
        $diseaseLog->load('pets');

        return response()->json($diseaseLog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DiseaseLog  $diseaseLog
     * @return JsonResponse
     */
    public function destroy(DiseaseLog $diseaseLog): JsonResponse
    {
        // Delete the disease log record.
        $diseaseLog->delete();

        return response()->json(null, 204);
    }
}
