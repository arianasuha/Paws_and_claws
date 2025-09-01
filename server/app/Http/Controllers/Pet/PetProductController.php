<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\PetProductRegisterRequest;
use App\Http\Requests\Pet\PetProductUpdateRequest;
use App\Models\PetProduct;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PetProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    private function imageHandler(Request $request, array &$validated, ?PetProduct $petproduct = null): void
    {
        if ($request->hasFile('image_url')) {
            if ($petproduct && $petproduct->image_url) {
                $this->deleteOldImage($petproduct->image_url);
            }
            $path = $request->file('image_url')->store('profile_images', 'public');
            $validated['image_url'] = Storage::url($path);
        } else if (array_key_exists('image_url', $validated) && is_null($validated['image_url'])) {
            // If remove Image button is pressed we will send image_url null
            // otherwise the key won't be sent
            if ($petproduct && $petproduct->image_url) {
                $this->deleteOldImage($petproduct->image_url);
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

    /**
     * @OA\Get(
     * path="/api/pet-products",
     * operationId="getPetProductsList",
     * tags={"PetProducts"},
     * summary="Get list of all pet products",
     * description="Returns a paginated list of all pet products, with an option to specify the number of items per page.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * description="Number of items per page",
     * required=false,
     * @OA\Schema(type="integer", default=10)
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/PetProductPaginatedResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Server error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            $petProducts = PetProduct::with('category')->paginate($perPage);

            return response()->json($petProducts, 200);
        } catch (\Exception $e) {
            // Log the full exception for detailed error tracking
            Log::error('Error retrieving pet products: ' . $e->getMessage());

            // Return the specific error message to the user for immediate debugging
            return response()->json([
                'errors' => 'Failed to retrieve pet products: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Post(
     * path="/api/pet-products",
     * operationId="createPetProduct",
     * tags={"PetProducts"},
     * summary="Create a new pet product (Admin only)",
     * description="Creates a new pet product. Requires admin authentication.",
     * security={{"sanctum":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Pet product data",
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(ref="#/components/schemas/PetProductRegisterRequest")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Pet product created successfully",
     * @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - User is not an admin",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Server error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function createProduct(PetProductRegisterRequest $request): JsonResponse
    {
        try {
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to perform this action.'
                ], 403);
            }

            $validatedData = $request->validated();
            $this->imageHandler($request, $validatedData);

            PetProduct::create($validatedData);

            return response()->json([
                "success" => "Pet Product created successfully.",
            ], 201);
        } catch (\Exception $e) {
            // Log the full exception for detailed error tracking
            Log::error('Error creating pet product: ' . $e->getMessage());

            // Return the specific error message to the user for immediate debugging
            return response()->json([
                'errors' => 'Failed to create pet product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/pet-products/{petProductid}",
     * operationId="getPetProductById",
     * tags={"PetProducts"},
     * summary="Get a single pet product",
     * description="Returns a single pet product by its ID.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="petProductid",
     * in="path",
     * required=true,
     * description="ID of the pet product",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/PetProduct")
     * ),
     * @OA\Response(
     * response=404,
     * description="Pet product not found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Server error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */

    public function show(string $petProductid): JsonResponse
    {
        try {
            $petProduct = PetProduct::with('category')->find($petProductid);

            if (!$petProduct) {
                return response()->json([
                    'errors' => 'Pet product not found.'
                ], 404);
            }
            return response()->json($petProduct, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve pet product.'
            ], 500);
        }
    }

    /**
     * @OA\Patch(
     * path="/api/pet-products/{petProductid}",
     * operationId="updatePetProduct",
     * tags={"PetProducts"},
     * summary="Update a pet product (Admin only)",
     * description="Updates an existing pet product by its ID. Requires admin authentication. Using POST with _method spoofing for file uploads.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="petProductid",
     * in="path",
     * required=true,
     * description="ID of the pet product to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Pet product data to update",
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(ref="#/components/schemas/PetProductUpdateRequest")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Pet product updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string", example="Pet product updated successfully."),
     * @OA\Property(property="pet_product", ref="#/components/schemas/PetProduct")
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - User is not an admin",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Pet product not found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Server error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function update(PetProductUpdateRequest $request, string $petProductid): JsonResponse
    {
        try {
            $petProduct = PetProduct::find($petProductid);

            if (!$petProduct) {
                return response()->json([
                    'errors' => 'Pet product not found.'
                ], 404);
            }
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to perform this action.'
                ], 403);
            }

            $validatedData = $request->validated();
            $this->imageHandler($request, $validatedData, $petProduct);

            $petProduct->update($validatedData);

            return response()->json([
                'success' => 'Pet product updated successfully.',
                'pet_product' => $petProduct->fresh(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to update pet product.'
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/pet-products/{petProductid}",
     * operationId="deletePetProduct",
     * tags={"PetProducts"},
     * summary="Delete a pet product (Admin only)",
     * description="Deletes a pet product by its ID. Requires admin authentication.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="petProductid",
     * in="path",
     * required=true,
     * description="ID of the pet product to delete",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Pet product deleted successfully"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - User is not an admin",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Pet product not found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Server error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */

    public function destroy(string $petProductid): JsonResponse
    {
        try {
            $petProduct = PetProduct::find($petProductid);

            if (!$petProduct) {
                return response()->json([
                    'errors' => 'Pet product not found.'
                ], 404);
            }
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to perform this action.'
                ], 403);
            }

            if ($petProduct->image_url) {
                $this->deleteOldImage($petProduct->image_url);
            }

            $petProduct->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to delete pet product.'
            ], 500);
        }
    }
}
