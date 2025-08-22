<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\PetMarketRegisterRequest;
use App\Http\Requests\Pet\PetMarketUpdateRequest;
use App\Models\PetMarket;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Storage;

class PetMarketController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/pet-markets",
     * operationId="getPetMarkets",
     * tags={"PetMarket"},
     * summary="Get all pet market listings",
     * description="Returns a paginated list of all pet market listings, including associated pet data.",
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/PetMarketPaginatedResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Server error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function index()
    {
        try {
            $petMarkets = PetMarket::with('pet')->paginate(10);
            return response()->json($petMarkets, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching pet market listings: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve pet market listings.'], 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/pet-markets",
     * operationId="createPetMarketListing",
     * tags={"PetMarket"},
     * summary="Create a new pet market listing",
     * description="Registers a new pet and creates a market listing for the authenticated user.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * ref="#/components/schemas/PetMarketRegisterRequest"
     * )
     * ),
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * ref="#/components/schemas/PetMarketRegisterRequest"
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Pet market listing created successfully.",
     * @OA\JsonContent(
     * @OA\Property(property="pet_market", ref="#/components/schemas/PetMarket"),
     * @OA\Property(property="message", type="string", example="Pet market listing created successfully.")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     */
    public function create(PetMarketRegisterRequest $request)
    {
        try {
            $petData = $request->input('pet');
            $marketData = $request->input('market');

            if (isset($marketData['type']) && isset($marketData['status'])) {
                if ($marketData['type'] === 'adoption' && !in_array($marketData['status'], ['available', 'adopted'])) {
                    return response()->json(['error' => 'Invalid status for adoption. Status must be available, adopted.'], 422);
                }
                if ($marketData['type'] === 'sale' && !in_array($marketData['status'], ['available', 'sold'])) {
                    return response()->json(['error' => 'Invalid status for sale. Status must be available, sold.'], 422);
                }
            }

            if ($request->hasFile('pet.image_url')) {
                $imagePath = $request->file('pet.image_url')->store('pet-images', 'public');
                $petData['image_url'] = Storage::url($imagePath);
            }

            $pet = Pet::create(array_merge($petData, ['user_id' => Auth::id()]));
            $petMarket = PetMarket::create(array_merge($marketData, [
                'user_id' => Auth::id(),
                'pet_id' => $pet->id,
            ]));

            return response()->json([
                'pet_market' => $petMarket->load('pet'),
                'message' => 'Pet market listing created successfully.'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating pet market listing: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create pet market listing.'], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/pet-markets/{id}",
     * operationId="showPetMarket",
     * tags={"PetMarket"},
     * summary="Get a specific pet market listing",
     * description="Returns a single pet market listing by its ID, including associated pet data.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the pet market listing to retrieve",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/PetMarket")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Server error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function show($id)
    {
        try {
            $petMarket = PetMarket::with('pet')->find($id);

            if (!$petMarket) {
                return response()->json(['error' => 'Pet market listing not found.'], 404);
            }
            return response()->json($petMarket, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching pet market listing: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve pet market listing.'], 500);
        }
    }

    /**
     * @OA\Patch(
     * path="/api/pet-markets/{id}",
     * operationId="updatePetMarketListing",
     * tags={"PetMarket"},
     * summary="Update an existing pet market listing",
     * description="Updates a pet market listing. Supports multipart/form-data with _method spoofing.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the pet market listing to update"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * ref="#/components/schemas/PetMarketUpdateRequest"
     * )
     * ),
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * ref="#/components/schemas/PetMarketUpdateRequest"
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Pet market listing updated successfully."
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     */
    public function update(PetMarketUpdateRequest $request, $id)
    {
        try {
            $petMarket = PetMarket::find($id);

            if (!$petMarket) {
                return response()->json(['error' => 'Pet market listing not found.'], 404);
            }
            if ($petMarket->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized to update this listing.'], 403);
            }

            $validatedData = $request->validated();

            $petMarket->update($validatedData);

            return response()->json([
                'success' => 'Pet market listing updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating pet market listing: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update pet market listing.'], 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/pet-markets/{id}",
     * operationId="deletePetMarket",
     * tags={"PetMarket"},
     * summary="Delete a pet market listing and its associated pet",
     * description="Deletes a pet market listing and the related pet data by ID.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the pet market listing to delete",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Successful operation (no content)",
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Server error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function destroy($id)
    {
        try {
            $petMarket = PetMarket::find($id);

            if (!$petMarket) {
                return response()->json(['error' => 'Pet market listing not found.'], 404);
            }

            if ($petMarket->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized to delete this listing.'], 403);
            }

            if ($petMarket->pet->image_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $petMarket->pet->image_url));
            }
            $petMarket->pet->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting pet market listing: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete pet market listing.'], 500);
        }
    }
}