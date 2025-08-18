<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\ReportLostPet;
use App\Http\Requests\Pet\ReportLostPetRegisterRequest;
use App\Http\Requests\Pet\ReportLostPetUpdateRequest;
use Illuminate\Http\JsonResponse;

class ReportLostPetController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/report-lost-pets",
     * tags={"Lost Pet Reports"},
     * summary="Get a list of all lost pet reports",
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ReportLostPet"))
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieve all lost pet reports with associated user and pet details.
        $reports = ReportLostPet::with(['user', 'pet'])->get();

        return response()->json($reports);
    }

    /**
     * @OA\Post(
     * path="/api/report-lost-pets",
     * tags={"Lost Pet Reports"},
     * summary="Store a newly created lost pet report",
     * security={{"sanctum":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/ReportLostPetRegisterRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Report created successfully",
     * @OA\JsonContent(ref="#/components/schemas/ReportLostPet")
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
     * Store a newly created resource in storage.
     *
     * @param  ReportLostPetRegisterRequest  $request
     * @return JsonResponse
     */
    public function store(ReportLostPetRegisterRequest $request): JsonResponse
    {
        // Get the authenticated user's ID
        $userId = $request->user()->id;

        // Create a new lost pet report using the validated data and the user ID.
        $report = ReportLostPet::create([
            'user_id' => $userId,
            'location' => $request->location,
            'date_lost' => $request->date_lost,
            'pet_id' => $request->pet_id,
            'status' => $request->status,
        ]);

        // Load the relationships for the response.
        $report->load(['user', 'pet']);

        return response()->json($report, 201);
    }

    /**
     * @OA\Get(
     * path="/api/report-lost-pets/{reportLostPet}",
     * tags={"Lost Pet Reports"},
     * summary="Get a lost pet report by ID",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="reportLostPet",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the lost pet report to retrieve"
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/ReportLostPet")
     * ),
     * @OA\Response(
     * response=404,
     * description="Report not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Display the specified resource.
     *
     * @param  ReportLostPet  $reportLostPet
     * @return JsonResponse
     */
    public function show(ReportLostPet $reportLostPet): JsonResponse
    {
        // Load the relationships for the response.
        $reportLostPet->load(['user', 'pet']);

        return response()->json($reportLostPet);
    }

    /**
     * @OA\Put(
     * path="/api/report-lost-pets/{reportLostPet}",
     * tags={"Lost Pet Reports"},
     * summary="Update an existing lost pet report",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="reportLostPet",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the report to update"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/ReportLostPetUpdateRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Report updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/ReportLostPet")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden: User does not own this report"
     * ),
     * @OA\Response(
     * response=404,
     * description="Report not found"
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
     * Update the specified resource in storage.
     *
     * @param  ReportLostPetUpdateRequest  $request
     * @param  ReportLostPet  $reportLostPet
     * @return JsonResponse
     */
    public function update(ReportLostPetUpdateRequest $request, ReportLostPet $reportLostPet): JsonResponse
    {
        // Authorization check to ensure only the owner can update the report.
        if ($request->user()->id !== $reportLostPet->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Update the report with the validated data.
        $reportLostPet->update($request->validated());

        // Load the relationships for the response.
        $reportLostPet->load(['user', 'pet']);

        return response()->json($reportLostPet);
    }

    /**
     * @OA\Delete(
     * path="/api/report-lost-pets/{reportLostPet}",
     * tags={"Lost Pet Reports"},
     * summary="Remove the specified lost pet report",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="reportLostPet",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the report to delete"
     * ),
     * @OA\Response(
     * response=204,
     * description="Report deleted successfully"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden: User does not own this report"
     * ),
     * @OA\Response(
     * response=404,
     * description="Report not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  ReportLostPet  $reportLostPet
     * @return JsonResponse
     */
    public function destroy(ReportLostPet $reportLostPet): JsonResponse
    {
        // Authorization check to ensure only the owner can delete the report.
        if (request()->user()->id !== $reportLostPet->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete the report.
        $reportLostPet->delete();

        return response()->json(null, 204);
    }
}
