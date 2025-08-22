<?php

namespace App\Http\Controllers\Medical;

use App\Http\Controllers\Controller;
use App\Models\MedicalLog;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Medical\MedicalLogRegisterRequest;
use App\Http\Requests\Medical\MedicalLogUpdateRequest;

class MedicalLogController extends Controller
{

    public function index(): JsonResponse
    {
        $medicalLogs = MedicalLog::with(['appointment', 'pets'])->get();

        return response()->json($medicalLogs);
    }


    public function store(MedicalLogRegisterRequest $request): JsonResponse
    {

        $medicalLog = MedicalLog::create($request->validated());


        $medicalLog->load(['appointment', 'pets']);

        return response()->json($medicalLog, 201);
    }

    public function show(MedicalLog $medicalLog): JsonResponse
    {

        $medicalLog->load(['appointment', 'pets']);

        return response()->json($medicalLog);
    }


    public function update(MedicalLogUpdateRequest $request, MedicalLog $medicalLog): JsonResponse
    {

        $medicalLog->update($request->validated());


        $medicalLog->load(['appointment', 'pets']);

        return response()->json($medicalLog);
    }


    public function destroy(MedicalLog $medicalLog): JsonResponse
    {

        $medicalLog->delete();

        return response()->json(null, 204);
    }
}
