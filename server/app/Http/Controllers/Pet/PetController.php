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
    private function imageHandler(Request $request, array &$validated, ?Pet $pet = null): void
    {
        if ($request->hasFile('image_url')) {
            if ($pet && $pet->image_url) {
                $this->deleteOldImage($pet->image_url);
            }
            $path = $request->file('image_url')->store('pet_images', 'public');
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
     * @OA\Get(
     * path="/api/pets",
     * operationId="indexPets",
     * tags={"Pets"},
     * summary="Get a list of all pets with filtering",
     * description="Returns a paginated list of all pets, filtered by search query (name, species, breed) and gender. Requires authentication.",
     * security={{"sanctum": {}}},
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
     * description="Search query for partial matching on name, species, and breed",
     * required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="gender",
     * in="query",
     * description="Filter by gender",
     * required=false,
     * @OA\Schema(type="string", enum={"male", "female"})
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/PetPaginatedResponse")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Pet::query();

            if ($request->has('search')) {
                $searchTerm = '%' . $request->input('search') . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'ilike', $searchTerm)
                        ->orWhere('species', 'ilike', $searchTerm)
                        ->orWhere('breed', 'ilike', $searchTerm);
                });
            }

            if ($request->has('gender')) {
                $query->where('gender', $request->input('gender'));
            }

            $pets = $query->paginate(10);

            return response()->json($pets, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     * path="/api/pets",
     * operationId="createPet",
     * tags={"Pets"},
     * summary="Create a new pet",
     * description="Registers a new pet for the authenticated user.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * required={"name", "gender", "species", "dob"},
     * @OA\Property(property="name", type="string"),
     * @OA\Property(property="gender", type="string", enum={"male", "female"}),
     * @OA\Property(property="species", type="string"),
     * @OA\Property(property="breed", type="string", nullable=true),
     * @OA\Property(property="dob", type="string", format="date"),
     * @OA\Property(property="image_url", type="string", format="binary", description="Pet's profile image file"),
     * @OA\Property(property="height", type="number", format="float", nullable=true),
     * @OA\Property(property="weight", type="number", format="float", nullable=true),
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Pet created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Pet")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
     */
    public function createPet(PetRegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id(); // Ensure user_id is set to authenticated user

        $this->imageHandler($request, $validatedData);

        Pet::create($validatedData);

        return response()->json([
            "success" => "Pet created successfully.",
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     * path="/api/pets/{pet}",
     * operationId="showPet",
     * tags={"Pets"},
     * summary="Get a single pet by ID",
     * description="Returns a single pet by its ID. Requires authentication.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="pet",
     * in="path",
     * required=true,
     * description="ID of the pet to retrieve",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Pet")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=404,
     * description="Pet not found"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
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
     * @OA\Post(
     * path="/api/pets/{pet}",
     * operationId="updatePet",
     * tags={"Pets"},
     * summary="Update a pet by ID with method spoofing",
     * description="Updates an authenticated user's pet. Requires the user to be the owner of the pet. Uses POST with _method spoofing for file uploads.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="pet",
     * in="path",
     * required=true,
     * description="ID of the pet to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(
     * property="_method",
     * type="string",
     * example="PATCH",
     * description="Method spoofing for PATCH request"
     * ),
     * @OA\Property(property="name", type="string", nullable=true),
     * @OA\Property(property="gender", type="string", enum={"male", "female"}, nullable=true),
     * @OA\Property(property="species", type="string", nullable=true),
     * @OA\Property(property="breed", type="string", nullable=true),
     * @OA\Property(property="dob", type="string", format="date", nullable=true),
     * @OA\Property(property="height", type="number", format="float", nullable=true),
     * @OA\Property(property="weight", type="number", format="float", nullable=true)
     * )
     * ),
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * @OA\Property(
     * property="_method",
     * type="string",
     * example="PATCH",
     * description="Method spoofing for PATCH request"
     * ),
     * @OA\Property(property="name", type="string", nullable=true),
     * @OA\Property(property="gender", type="string", enum={"male", "female"}, nullable=true),
     * @OA\Property(property="species", type="string", nullable=true),
     * @OA\Property(property="breed", type="string", nullable=true),
     * @OA\Property(property="dob", type="string", format="date", nullable=true),
     * @OA\Property(property="image_url", type="string", format="binary", nullable=true, description="Pet's profile image file"),
     * @OA\Property(property="height", type="number", format="float", nullable=true),
     * @OA\Property(property="weight", type="number", format="float", nullable=true)
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Pet updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string"),
     * @OA\Property(property="pet", ref="#/components/schemas/Pet")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=404,
     * description="Pet not found"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
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
     *
     * @OA\Delete(
     * path="/api/pets/{pet}",
     * operationId="deletePet",
     * tags={"Pets"},
     * summary="Delete a pet",
     * description="Deletes an authenticated user's pet. Requires the user to be the owner of the pet.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="pet",
     * in="path",
     * required=true,
     * description="ID of the pet to delete",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Pet deleted successfully"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=404,
     * description="Pet not found"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
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