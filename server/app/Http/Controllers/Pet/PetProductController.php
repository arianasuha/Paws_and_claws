<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\PetProductRegisterRequest;
use App\Http\Requests\Pet\PetProductUpdateRequest;
use App\Models\PetProduct;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PetProductController extends Controller
{
    /**
     * PetProductController constructor.
     * This middleware ensures that all methods in this controller
     * can only be accessed by authenticated users.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Handle image upload and deletion for PetProducts.
     *
     * @param Request $request
     * @param array $validated
     * @param PetProduct|null $petProduct
     * @return void
     */
    private function imageHandler(Request $request, array &$validated, ?PetProduct $petProduct = null): void
    {
        if ($request->hasFile('image_url')) {
            if ($petProduct && $petProduct->image_url) {
                $this->deleteOldImage($petProduct->image_url);
            }
            $path = $request->file('image_url')->store('product_images', 'public');
            $validated['image_url'] = Storage::url($path);
        } else if (array_key_exists('image_url', $validated) && is_null($validated['image_url'])) {
            if ($petProduct && $petProduct->image_url) {
                $this->deleteOldImage($petProduct->image_url);
            }
            $validated['image_url'] = null;
        } else {
            unset($validated['image_url']);
        }
    }

    /**
     * Delete an image from storage.
     *
     * @param string|null $imageUrl
     * @return void
     */
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
     * tags={"PetProducts"},
     * summary="Get a paginated list of pet products",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * required=false,
     * @OA\Schema(type="integer", default=10),
     * description="Number of products to return per page"
     * ),
     * @OA\Parameter(
     * name="page",
     * in="query",
     * required=false,
     * @OA\Schema(type="integer", default=1),
     * description="Page number"
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/PetProductPaginatedResponse")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Display a listing of the pet products.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            $petProducts = PetProduct::paginate($perPage);

            return response()->json($petProducts, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve pet products.'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/pet-products",
     * tags={"PetProducts"},
     * summary="Create a new pet product (Admin only)",
     * security={{"sanctum":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * required={"name","description","price","stock"},
     * @OA\Property(property="name", type="string", example="Dog Food"),
     * @OA\Property(property="description", type="string", example="A high-quality dog food for all breeds."),
     * @OA\Property(property="price", type="number", format="float", example="25.50"),
     * @OA\Property(property="stock", type="integer", example="100"),
     * @OA\Property(property="image_url", type="string", format="binary", description="Image file of the product"),
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Product created successfully",
     * @OA\JsonContent(ref="#/components/schemas/PetProduct")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden: User does not have admin privileges"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Store a newly created pet product in storage.
     *
     * @param PetProductRegisterRequest $request
     * @return JsonResponse
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

            $petProduct = PetProduct::create($validatedData);

            return response()->json($petProduct, 201);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to create pet product.'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/pet-products/{id}",
     * tags={"PetProducts"},
     * summary="Get a pet product by ID",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the product to retrieve"
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/PetProduct")
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Display the specified pet product.
     *
     * @param PetProduct $petProduct
     * @return JsonResponse
     */
    public function show(string $petProductid): JsonResponse
    {
        try {
            $petProduct = PetProduct::find($petProductid);

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
     * @OA\Post(
     * path="/api/pet-products/{id}",
     * tags={"PetProducts"},
     * summary="Update an existing pet product (Admin only)",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the product to update"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="multipart/form-data",
     * @OA\Schema(
     * @OA\Property(property="name", type="string", example="New Dog Food Name"),
     * @OA\Property(property="description", type="string", example="Updated description."),
     * @OA\Property(property="price", type="number", format="float", example="29.99"),
     * @OA\Property(property="stock", type="integer", example="150"),
     * @OA\Property(property="image_url", type="string", format="binary", description="New image file of the product"),
     * @OA\Property(property="_method", type="string", example="PATCH", description="Method override for form submission"),
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Product updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/PetProduct")
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Update the specified pet product in storage.
     *
     * @param PetProductUpdateRequest $request
     * @param PetProduct $petProduct
     * @return JsonResponse
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
     * path="/api/pet-products/{id}",
     * tags={"PetProducts"},
     * summary="Delete a pet product (Admin only)",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the product to delete"
     * ),
     * @OA\Response(
     * response=204,
     * description="Product deleted successfully"
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Remove the specified pet product from storage.
     *
     * @param PetProduct $petProduct
     * @return JsonResponse
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
