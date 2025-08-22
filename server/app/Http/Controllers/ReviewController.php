<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Http\Requests\ReviewRegisterRequest;
use App\Http\Requests\ReviewUpdateRequest;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{

    public function index(): JsonResponse
    {
        $reviews = Review::all();

        return response()->json([
            'status' => 'success',
            'reviews' => $reviews
        ]);
    }


    public function store(ReviewRegisterRequest $request): JsonResponse
    {
        $review = Review::create($request->validated());

        return response()->json([
            'status' => 'success',
            'review' => $review
        ], 201);
    }


    public function show(Review $review): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'review' => $review
        ]);
    }


    public function update(ReviewUpdateRequest $request, Review $review): JsonResponse
    {
        $review->update($request->validated());

        return response()->json([
            'status' => 'success',
            'review' => $review
        ]);
    }


    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted successfully.'
        ], 204);
    }
}
