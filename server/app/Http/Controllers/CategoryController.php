<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRegisterRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     * This middleware ensures that all methods in this controller
     * can only be accessed by authenticated users.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     * path="/api/categories",
     * tags={"Categories"},
     * summary="Get a list of all categories",
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Category")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Display a listing of all categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::all();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve categories.'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/categories",
     * tags={"Categories"},
     * summary="Create a new category (Admin only)",
     * security={{"sanctum":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name"},
     * @OA\Property(property="name", type="string", example="Toys"),
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Category created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Category")
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
     * Store a newly created category in storage.
     *
     * @param CategoryRegisterRequest $request
     * @return JsonResponse
     */
    public function create(CategoryRegisterRequest $request): JsonResponse
    {
        try {
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to create category.'
                ], 403);
            }

            $validatedData = $request->validated();
            $category = Category::create($validatedData);

            return response()->json($category, 201);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to create category.'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/categories/{id}",
     * tags={"Categories"},
     * summary="Get a category by ID",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the category to retrieve"
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Category")
     * ),
     * @OA\Response(
     * response=404,
     * description="Category not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Display the specified category.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'errors' => 'Category not found.'
                ], 404);
            }
            return response()->json($category, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve category.'
            ], 500);
        }
    }

    /**
     * @OA\Put(
     * path="/api/categories/{id}",
     * tags={"Categories"},
     * summary="Update an existing category (Admin only)",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the category to update"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Food"),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Category updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Category")
     * ),
     * @OA\Response(
     * response=404,
     * description="Category not found"
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
     * Update the specified category in storage.
     *
     * @param CategoryUpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'errors' => 'Category not found.'
                ], 404);
            }
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to perform this action.'
                ], 403);
            }

            $validatedData = $request->validated();
            $category->update($validatedData);

            return response()->json($category->fresh(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to update category.'
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/categories/{id}",
     * tags={"Categories"},
     * summary="Delete a category (Admin only)",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the category to delete"
     * ),
     * @OA\Response(
     * response=204,
     * description="Category deleted successfully"
     * ),
     * @OA\Response(
     * response=404,
     * description="Category not found"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden: User does not have admin privileges"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Remove the specified category from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'errors' => 'Category not found.'
                ], 404);
            }
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to perform this action.'
                ], 403);
            }

            $category->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to delete category.'
            ], 500);
        }
    }
}
