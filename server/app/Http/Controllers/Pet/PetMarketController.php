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
use Illuminate\Http\Request;

class PetMarketController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/pet-markets",
     * operationId="getPetMarkets",
     * tags={"PetMarket"},
     * summary="Get all pet market listings with advanced filtering and sorting",
     * description="Returns a paginated list of all pet market listings. Listings can be filtered by pet attributes (name, species, breed, gender) and sorted by date or fee. Full pet data is returned for each listing.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="page",
     * in="query",
     * description="Page number for pagination",
     * required=false,
     * @OA\Schema(type="integer", default=1)
     * ),
     * @OA\Parameter(
     * name="search",
     * in="query",
     * description="Search query for partial matching on pet name, species, and breed.",
     * required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="gender",
     * in="query",
     * description="Filter by pet's gender.",
     * required=false,
     * @OA\Schema(type="string", enum={"male", "female"})
     * ),
     * @OA\Parameter(
     * name="type",
     * in="query",
     * description="Filter by listing type (e.g., 'sale' or 'adoption')",
     * required=false,
     * @OA\Schema(
     * type="string",
     * enum={"sale", "adoption"}
     * )
     * ),
     * @OA\Parameter(
     * name="status",
     * in="query",
     * description="Filter by listing status (e.g., 'available', 'adopted', 'sold')",
     * required=false,
     * @OA\Schema(
     * type="string",
     * enum={"available", "adopted", "sold"}
     * )
     * ),
     * @OA\Parameter(
     * name="sortBy",
     * in="query",
     * description="Column to sort by ('date' or 'fee')",
     * required=false,
     * @OA\Schema(
     * type="string",
     * enum={"date", "fee"},
     * default="date"
     * )
     * ),
     * @OA\Parameter(
     * name="sortDirection",
     * in="query",
     * description="Sort direction ('asc' or 'desc')",
     * required=false,
     * @OA\Schema(
     * type="string",
     * enum={"asc", "desc"},
     * default="desc"
     * )
     * ),
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
    public function index(Request $request)
    {
        try {
            $query = PetMarket::with([
                'pet',
                'user' => function ($query) {
                    $query->select('id', 'username');
                }
            ]);

            if ($request->has('search')) {
                $searchTerm = '%' . $request->input('search') . '%';
                $query->whereHas('pet', function ($q) use ($searchTerm) {
                    $q->where('name', 'ilike', $searchTerm)
                        ->orWhere('species', 'ilike', $searchTerm)
                        ->orWhere('breed', 'ilike', $searchTerm);
                });
            }

            if ($request->has('gender')) {
                $query->whereHas('pet', function ($q) use ($request) {
                    $q->where('gender', $request->input('gender'));
                });
            }
            
            if ($request->has('type')) {
                $query->where('type', $request->input('type'));
            }

            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $sortBy = $request->input('sortBy', 'date');
            $sortDirection = $request->input('sortDirection', 'desc');

            $allowedSortColumns = ['date', 'fee'];
            if (!in_array($sortBy, $allowedSortColumns)) {
                $sortBy = 'date';
            }

            if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
                $sortDirection = 'desc';
            }

            $query->orderBy($sortBy, $sortDirection);

            $petMarkets = $query->paginate(10);

            return response()->json($petMarkets, 200);

        } catch (\Exception $e) {
            Log::error('Error fetching pet market listings: ' . $e->getMessage());
            return response()->json(['errors' => 'Could not retrieve pet market listings.'], 500);
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
            $validated = $request->validated();

            $pet = Pet::find($validated['pet_id']);

            if ($pet->user_id !== Auth::user()->id) {
                return response()->json([
                    'errors' => 'You are not authorized to create a market listing for this pet.'
                ], 403);
            }

            if (isset($validated['type']) && isset($validated['status'])) {
                if ($validated['type'] === 'adoption' && !in_array($validated['status'], ['available', 'adopted'])) {
                    return response()->json(['error' => 'Invalid status for adoption. Status must be available, adopted.'], 422);
                }
                if ($validated['type'] === 'sale' && !in_array($validated['status'], ['available', 'sold'])) {
                    return response()->json(['error' => 'Invalid status for sale. Status must be available, sold.'], 422);
                }
            }

            PetMarket::create(array_merge($validated, [
                'user_id' => Auth::id(),
            ]));

            return response()->json([
                'success' => 'Pet market listing created successfully.'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating pet market listing: ' . $e->getMessage());
            return response()->json(['errors' => 'Could not create pet market listing.'], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/pet-markets/{pet_id}",
     * operationId="showPetMarket",
     * tags={"PetMarket"},
     * summary="Get a specific pet market listing",
     * description="Returns a single pet market listing by its ID, including associated pet data.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="pet_id",
     * in="path",
     * required=true,
     * description="Pet ID of the pet market listing to retrieve",
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
    public function show(string $pet_id)
    {
        try {
            $petMarket = PetMarket::where('pet_id', $pet_id)->first();

            if (!$petMarket) {
                return response()->json(['errors' => 'Pet market listing not found.'], 404);
            }
            return response()->json($petMarket, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching pet market listing: ' . $e->getMessage());
            return response()->json(['errors' => 'Could not retrieve pet market listing.'], 500);
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

            if ($petMarket->user_id !== Auth::id() && !Auth::user()->is_admin) {
                return response()->json(['error' => 'Unauthorized to delete this listing.'], 403);
            }

            $petMarket->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting pet market listing: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete pet market listing.'], 500);
        }
    }
}