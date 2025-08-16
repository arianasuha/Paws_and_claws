<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Pet\PetProductRegisterRequest;
use App\Http\Requests\Pet\PetProductUpdateRequest;
use App\Models\PetProduct;
use Illuminate\Support\Facades\Storage;

class PetProductController extends Controller
{
    /**
     * Handle image upload and deletion for PetProducts.
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
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $petProducts = PetProduct::paginate(10);
            return response()->json($petProducts, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * createProduct in storage.
     */
    public function createProduct(PetProductRegisterRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('image_url')) {
                $imagePath = $request->file('image_url')->store('product_images', 'public');
                $validatedData['image_url'] = Storage::url($imagePath);
            } else {
                $validatedData['image_url'] = null;
            }

            $petProduct = PetProduct::create($validatedData);

            return response()->json($petProduct, 201);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $petProduct): JsonResponse
    {
        try {
            $foundPetProduct = PetProduct::find($petProduct);

            if (!$foundPetProduct) {
                return response()->json(['error' => 'Pet product not found'], 404);
            }

            return response()->json($foundPetProduct, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PetProductUpdateRequest $request, string $petProduct): JsonResponse
    {
        try {
            $foundPetProduct = PetProduct::find($petProduct);

            if (!$foundPetProduct) {
                return response()->json(['error' => 'Pet product not found'], 404);
            }

            $validated = $request->validated();
            $this->imageHandler($request, $validated, $foundPetProduct);

            $foundPetProduct->update($validated);

            return response()->json([
                'success' => 'Pet product updated successfully.',
                'pet_product' => $foundPetProduct->fresh(),
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
    public function destroy(string $petProduct): JsonResponse
    {
        try {
            $foundPetProduct = PetProduct::find($petProduct);

            if (!$foundPetProduct) {
                return response()->json(['error' => 'Pet product not found'], 404);
            }

            if ($foundPetProduct->image_url) {
                $this->deleteOldImage($foundPetProduct->image_url);
            }

            $foundPetProduct->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}