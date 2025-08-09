<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Pet\PetRegisterRequest;
use App\Http\Requests\Pet\PetUpdateRequest;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Create a new controller instance.
     */

    private function imageHandler(Request $request, array &$validated, ?Pet $pet = null): void
    {
        if ($request->hasFile('image_url')) {
            if ($pet && $pet->image_url) {
                $this->deleteOldImage($pet->image_url);
            }
            $path = $request->file('image_url')->store('profile_images', 'public');
            $validated['image_url'] = Storage::url($path);
        } else if (array_key_exists('image_url', $validated) && is_null($validated['image_url'])) {
            // If remove Image button is pressed we will send image_url null
            // otherwise the key won't be sent
            if ($pet && $pet->image_url) {
                $this->deleteOldImage($pet->image_url);
            }
            $validated['image_url'] = null;
        } else {
            unset($validated['image_url']);
        }
    }

    private function deleteOldImage(?string $imageUrl): void
    {
        if ($imageUrl) {
            $path = str_replace(Storage::url(''), '', $imageUrl);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $pets = Pet::paginate(10);
            return response()->json($pets, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createPet(PetRegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id(); // Ensure user_id is set to authenticated user

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('pet_images', 'public');
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
            $foundPet = Pet::find($pet);

            if (!$foundPet) {
                return response()->json(['error' => 'Pet not found'], 404);
            }

            return response()->json($foundPet, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PetUpdateRequest $request, string $pet): JsonResponse
    {
        try {
            $foundPet = Pet::find($pet);

            if (!$foundPet) {
                return response()->json(['error' => 'Pet not found'], 404);
            }

            if (Auth::id() !== $foundPet->user_id) {
                return response()->json(['error' => 'Unauthorized. You can only update your own pets.'], 403);
            }

            $validated = $request->validated();

            $this->imageHandler($request, $validated, $foundPet);
            $foundPet->update($validated);

            return response()->json([
                'success' => 'Pet information updated successfully.',
                'pet' => $foundPet->fresh(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $pet): JsonResponse
    {
        try {
            $foundPet = Pet::find($pet);

            if (!$foundPet) {
                return response()->json(['error' => 'Pet not found'], 404);
            }

            if (Auth::id() !== $foundPet->user_id) {
                return response()->json(['error' => 'Unauthorized. You can only update your own pets.'], 403);
            }

            if ($foundPet->image_url) {
                $this->deleteOldImage($foundPet->image_url);
            }
            $foundPet->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}