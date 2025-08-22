<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\PetDisease;
use App\Http\Requests\Pet\PetDiseaseRegisterRequest;
use App\Http\Requests\Pet\PetDiseaseUpdateRequest;
use Illuminate\Http\JsonResponse;

class PetDiseaseController extends Controller
{
    public function index(): JsonResponse
    {
        // Retrieve all pet disease records.
        // Eager load the 'pet' and 'disease' relationships to avoid the N+1 problem.
        $petDiseases = PetDisease::with('pet', 'disease')->get();
        return response()->json($petDiseases);
    }


    public function store(PetDiseaseRegisterRequest $request): JsonResponse
    {
        // The validated data is used to create a new PetDisease record.
        $petDisease = PetDisease::create($request->validated());

        // Re-fetch the newly created record with its relationships for the response.
        $petDisease->load('pet', 'disease');

        return response()->json($petDisease, 201);
    }


    public function show(PetDisease $petDisease): JsonResponse
    {
        // Eager load the relationships for the specific record.
        $petDisease->load('pet', 'disease');
        return response()->json($petDisease);
    }


    public function update(PetDiseaseUpdateRequest $request, PetDisease $petDisease): JsonResponse
    {
        // Update the pet disease record with the validated data from the request.
        $petDisease->update($request->validated());

        // Re-fetch the updated record with its relationships for the response.
        $petDisease->load('pet', 'disease');
        return response()->json($petDisease);
    }


    public function destroy(PetDisease $petDisease): JsonResponse
    {
        // Delete the pet disease record.
        $petDisease->delete();
        return response()->json(null, 204);
    }
}
