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
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


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


    private function deleteOldImage(?string $imageUrl): void
    {
        if ($imageUrl) {
            $path = str_replace(Storage::url(''), '', $imageUrl);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }


    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            $petProducts = PetProduct::with('category')->paginate($perPage);

            return response()->json($petProducts, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve pet products.'
            ], 500);
        }
    }


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
            return response()->json([
                'errors' => 'Failed to create pet product.'
            ], 500);
        }
    }


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
