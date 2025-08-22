<?php

namespace App\Http\Controllers\Medical;

use App\Http\Controllers\Controller;
use App\Models\DiseaseLog;
use App\Http\Requests\Medical\DiseaseLogRegisterRequest;
use App\Http\Requests\Medical\DiseaseLogUpdateRequest;
use Illuminate\Http\JsonResponse;

class DiseaseLogController extends Controller
{

    public function index(): JsonResponse
    {

        $diseaseLogs = DiseaseLog::with('pets')->get();

        return response()->json($diseaseLogs);
    }


    public function store(DiseaseLogRegisterRequest $request): JsonResponse
    {

        $diseaseLog = DiseaseLog::create($request->validated());


        if ($request->has('pet_ids')) {
            $diseaseLog->pets()->sync($request->input('pet_ids'));
        }


        $diseaseLog->load('pets');

        return response()->json($diseaseLog, 201);
    }


    public function show(DiseaseLog $diseaseLog): JsonResponse
    {
        // Eager load the 'pets' relationship for the specific disease log.
        $diseaseLog->load('pets');

        return response()->json($diseaseLog);
    }


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


    public function destroy(DiseaseLog $diseaseLog): JsonResponse
    {
        // Delete the disease log record.
        $diseaseLog->delete();

        return response()->json(null, 204);
    }
}
