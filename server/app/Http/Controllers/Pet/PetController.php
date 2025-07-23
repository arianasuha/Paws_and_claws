<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Pet\PetRegisterRequest;
use App\Http\Requests\Pet\PetUpdateRequest;

class PetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $pets = Pet::paginate(10);
            return response()->json($pets, 200);
        } catch (\Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function createPet(PetRegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('public/pet_images');
            $validatedData['image_url'] = Storage::url($imagePath);
        } else {
            $validatedData['image_url'] = null;
        }

        $pet = Pet::create($validatedData);

        return response()->json($pet, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $pet): JsonResponse
    {
        try {
            $foundPet = Pet::where('id', $pet)
            ->first();

            if (!$foundPet) {
                return response()->json(['error' => 'Pet not found'], 404);
            }

            return response()->json($foundPet, 200);

        } catch (\Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(PetUpdateRequest $request, string $pet): JsonResponse
    {
        try {
            $foundPet = Pet::where('id', $pet)->first();

            if (!$foundPet) {
                return response()->json(['error' => 'Pet not found'], 404);
            }

            if (Auth::id() !== $foundPet->owner_id) {
                return response()->json(['error' => 'Unauthorized. You can only update your own pets.'], 403);
            }

            $validated = $request->validated();

            $foundPet->update($validated);

            return response()->json([
                "success" => "Pet information updated successfully.",
                "pet" => $foundPet->fresh(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }

    }

    public function destroy(Request $request, string $pet): JsonResponse
    {

        try {
            $foundPet = Pet::where('id', $pet)
            ->first();

            if (!$foundPet) {
                return response()->json(['error' => 'Pet not found'], 404);
            }

            if (Auth::id() !== $foundPet->owner_id) {
                return response()->json(['error' => 'Unauthorized. You can only update your own pets.'], 403);
            }


            $foundPet->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
