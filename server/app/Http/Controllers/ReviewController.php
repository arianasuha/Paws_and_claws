<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Http\Requests\ReviewRegisterRequest;
use App\Http\Requests\ReviewUpdateRequest;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $reviews = Review::all();

        return response()->json([
            'status' => 'success',
            'reviews' => $reviews
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ReviewRegisterRequest  $request
     * @return JsonResponse
     */
    public function store(ReviewRegisterRequest $request): JsonResponse
    {
        $review = Review::create($request->validated());

        return response()->json([
            'status' => 'success',
            'review' => $review
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Review  $review
     * @return JsonResponse
     */
    public function show(Review $review): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'review' => $review
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ReviewUpdateRequest  $request
     * @param  Review  $review
     * @return JsonResponse
     */
    public function update(ReviewUpdateRequest $request, Review $review): JsonResponse
    {
        $review->update($request->validated());

        return response()->json([
            'status' => 'success',
            'review' => $review
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Review  $review
     * @return JsonResponse
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted successfully.'
        ], 204);
    }
}
