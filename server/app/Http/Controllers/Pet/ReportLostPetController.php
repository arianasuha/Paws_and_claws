<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\ReportLostPet;
use App\Http\Requests\Pet\ReportLostPetRegisterRequest;
use App\Http\Requests\Pet\ReportLostPetUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReportLostPetController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/reports/lost-pets",
     * operationId="getLostPetReports",
     * tags={"Lost Pets"},
     * summary="Get a list of all lost pet reports",
     * description="Returns a list of all lost pet reports, including associated user and pet details.",
     * security={{"sanctum": {}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/ReportLostPet")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function index(): JsonResponse
    {
        $reports = ReportLostPet::with([
            'user' => function ($query) {
                $query->select('id', 'username', 'email');
            },
            'pet'
        ])->get();

        return response()->json($reports);
    }

    /**
     * @OA\Post(
     * path="/api/reports/lost-pets",
     * operationId="createLostPetReport",
     * tags={"Lost Pets"},
     * summary="Create a new lost pet report",
     * description="Creates a new lost pet report for a specific pet.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="pet_id", type="integer", example=1),
     * @OA\Property(property="location", type="string", example="Hogwarts"),
     * @OA\Property(property="date_lost", type="string", format="date", example="2025-08-23"),
     * @OA\Property(property="status", type="string", enum={"missing", "found"}, default="missing")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Report created successfully",
     * @OA\JsonContent(ref="#/components/schemas/ReportLostPet")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     */
    public function store(ReportLostPetRegisterRequest $request): JsonResponse
    {
        $userId = $request->user()->id;

        // Access non-nested fields directly
        $report = ReportLostPet::create([
            'user_id' => $userId,
            'location' => $request->location,
            'date_lost' => $request->date_lost,
            'pet_id' => $request->pet_id,
            'status' => $request->status ?? 'missing',
        ]);

        $report->load(['user', 'pet']);

        return response()->json([
            "success" => "Lost Pet Report created successfully.",
        ], 201);
    }



    /**
     * @OA\Get(
     * path="/api/reports/lost-pets/{id}",
     * operationId="getLostPetReportById",
     * tags={"Lost Pets"},
     * summary="Get a specific lost pet report",
     * description="Returns a single lost pet report by its ID, with associated user and pet details.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the lost pet report",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/ReportLostPet")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Report not found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function show(string $id): JsonResponse
    {
        try {
            $reportLostPet = ReportLostPet::with([
                'user' => function ($query) {
                    $query->select('id', 'username', 'email');
                },
                'pet'
            ])->find($id);

            if (!$reportLostPet) {
                return response()->json(['error' => 'Lost Report not found.'], 404);
            }

            return response()->json($reportLostPet, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching lost pet report: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve lost pet report.'], 500);
        }
    }



    /**
     * @OA\Patch(
     * path="/api/reports/lost-pets/{id}",
     * operationId="updateLostPetReport",
     * tags={"Lost Pets"},
     * summary="Update a lost pet report",
     * description="Updates an existing lost pet report. Only the owner of the report can update it.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the lost pet report to update",
     * @OA\Schema(type="integer")
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
     * response=401,
     * description="Unauthenticated",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized to update this report",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Report not found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     */
    public function update(ReportLostPetUpdateRequest $request, string $id): JsonResponse
    {
        $reportLostPet = ReportLostPet::find($id);

        if (!$reportLostPet) {
            return response()->json(['error' => 'Lost Pet Report not found.'], 404);
        }

        if ($request->user()->id !== $reportLostPet->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reportLostPet->update($request->validated());

        $reportLostPet->load([
            'user' => function ($query) {
                $query->select('id', 'username', 'email');
            },
            'pet'
        ]);

        return response()->json($reportLostPet);
    }

    /**
     * @OA\Delete(
     * path="/api/reports/lost-pets/{id}",
     * operationId="deleteLostPetReport",
     * tags={"Lost Pets"},
     * summary="Delete a lost pet report",
     * description="Deletes a specific lost pet report. Only the owner can delete it.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the lost pet report to delete",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Report deleted successfully"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized to delete this report",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Report not found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $reportLostPet = ReportLostPet::find($id);

        if (!$reportLostPet) {
            return response()->json(['error' => 'Lost Pet Report not found.'], 404);
        }

        if (Auth::id() !== $reportLostPet->user_id) {
                return response()->json(['error' => 'Unauthorized. You can only delete your own your reports.'], 403);
            }


        $reportLostPet->delete();

        return response()->json(null, 204);
    }
}
