<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRegisterRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


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


    public function create(CategoryRegisterRequest $request): JsonResponse
    {
        try {
            if (!auth()->user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to perform this action.'
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
