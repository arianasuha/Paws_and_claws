<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\PetMarket;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Pet\PetMarketRegisterRequest;
use App\Http\Requests\Pet\PetMarketUpdateRequest;

class PetMarketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $petMarkets = PetMarket::with(['user', 'pet'])->paginate(10);
            return response()->json($petMarkets, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    /**
     * create a newly created resource in storage.
     */
    public function create(PetMarketRegisterRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            // Assign the authenticated user's ID to the request data
            $validatedData['user_id'] = Auth::id();

            $petMarket = PetMarket::create($validatedData);

            return response()->json($petMarket, 201);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $petMarket): JsonResponse
    {
        try {
            // Load relationships with the model
            $foundPetMarket = PetMarket::with(['user', 'pet'])->find($petMarket);

            if (!$foundPetMarket) {
                return response()->json(['error' => 'Pet market entry not found'], 404);
            }

            return response()->json($foundPetMarket, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PetMarketUpdateRequest $request, string $petMarket): JsonResponse
    {
        try {
            $foundPetMarket = PetMarket::find($petMarket);

            if (!$foundPetMarket) {
                return response()->json(['error' => 'Pet market entry not found'], 404);
            }

            // Check if the authenticated user owns the market entry
            if (Auth::id() !== $foundPetMarket->user_id) {
                return response()->json(['error' => 'Unauthorized. You can only update your own market entries.'], 403);
            }

            $validated = $request->validated();
            $foundPetMarket->update($validated);

            return response()->json([
                'success' => 'Pet market entry updated successfully.',
                // Return the fresh model instance with loaded relationships
                'pet_market' => $foundPetMarket->fresh(['user', 'pet']),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $petMarket): JsonResponse
    {
        try {
            $foundPetMarket = PetMarket::find($petMarket);

            if (!$foundPetMarket) {
                return response()->json(['error' => 'Pet market entry not found'], 404);
            }

            // Check if the authenticated user owns the market entry
            if (Auth::id() !== $foundPetMarket->user_id) {
                return response()->json(['error' => 'Unauthorized. You can only delete your own market entries.'], 403);
            }

            $foundPetMarket->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }
}
