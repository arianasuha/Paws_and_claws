<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\PetMedical;
use App\Http\Requests\Pet\PetMedicalRegisterRequest;
use App\Http\Requests\Pet\PetMedicalUpdateRequest;
use Illuminate\Http\JsonResponse;

class PetMedicalController extends Controller
{

    public function index(): JsonResponse
    {
        // Retrieve all pet medical records.
        // Eager load the 'pet' and 'medicallog' relationships to avoid the N+1 problem.
        $petMedicals = PetMedical::with('pet', 'medicallog')->get();
        return response()->json($petMedicals);
    }


    public function store(PetMedicalRegisterRequest $request): JsonResponse
    {
        // The validated data is used to create a new PetMedical record.
        $petMedical = PetMedical::create($request->validated());

        // Re-fetch the newly created record with its relationships for the response.
        $petMedical->load('pet', 'medicallog');

        return response()->json($petMedical, 201);
    }


    public function show(PetMedical $petMedical): JsonResponse
    {
        // Eager load the relationships for the specific record.
        $petMedical->load('pet', 'medicallog');
        return response()->json($petMedical);
    }


    public function update(PetMedicalUpdateRequest $request, PetMedical $petMedical): JsonResponse
    {
        // Update the pet medical record with the validated data from the request.
        $petMedical->update($request->validated());

        // Re-fetch the updated record with its relationships for the response.
        $petMedical->load('pet', 'medicallog');
        return response()->json($petMedical);
    }


    public function destroy(PetMedical $petMedical): JsonResponse
    {
        // Delete the pet medical record.
        $petMedical->delete();
        return response()->json(null, 204);
    }
}
