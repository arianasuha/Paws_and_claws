<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\ReportLostPet;
use App\Http\Requests\Pet\ReportLostPetRegisterRequest;
use App\Http\Requests\Pet\ReportLostPetUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReportLostPetController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/reports/lost-pets",
     * operationId="getLostPetReports",
     * tags={"Lost Pets"},
     * summary="Get a list of all lost pet reports with filtering and sorting",
     * description="Returns a list of all lost pet reports, with options to filter by status and location, and sort by date. Includes associated user and pet details.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="page",
     * in="query",
     * description="Page number for pagination",
     * required=false,
     * @OA\Schema(type="integer", default=1)
     * ),
     * @OA\Parameter(
     * name="status",
     * in="query",
     * description="Filter reports by status (e.g., 'missing' or 'found')",
     * required=false,
     * @OA\Schema(type="string", enum={"missing", "found"})
     * ),
     * @OA\Parameter(
     * name="location",
     * in="query",
     * description="Filter reports by a partial, case-insensitive match on location",
     * required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="sort_date",
     * in="query",
     * description="Sort reports by date_lost in ascending ('asc') or descending ('desc') order. Defaults to 'desc'.",
     * required=false,
     * @OA\Schema(type="string", enum={"asc", "desc"})
     * ),
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
    public function index(Request $request): JsonResponse
    {
        $reports = ReportLostPet::with([
            'user' => function ($query) {
                $query->select('id', 'username', 'email');
            },
            'pet'
        ])
        ->when($request->has('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->when($request->has('location'), function ($query) use ($request) {
            $query->where('location', 'ilike', '%' . $request->location . '%');
        })
        ->when($request->has('sort_date'), function ($query) use ($request) {
            $sortDirection = $request->sort_date === 'asc' ? 'asc' : 'desc';
            $query->orderBy('date_lost', $sortDirection);
        }, function ($query) {
            $query->orderBy('date_lost', 'desc');
        })
        ->paginate(10);

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
        $validated = $request->validated();
        $validated['user_id'] = $userId;

        ReportLostPet::create($validated);

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
                return response()->json(['errors' => 'Lost Report not found.'], 404);
            }

            return response()->json($reportLostPet, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching lost pet report: ' . $e->getMessage());
            return response()->json(['errors' => 'Failed to retrieve lost pet report.'], 500);
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
            return response()->json(['errors' => 'Lost Pet Report not found.'], 404);
        }

        if ($request->user()->id !== $reportLostPet->user_id) {
            return response()->json(['errors' => 'Unauthorized to update this report'], 403);
        }

        $reportLostPet->update($request->validated());

        $reportLostPet->load([
            'user' => function ($query) {
                $query->select('id', 'username', 'email');
            },
            'pet'
        ]);

        return response()->json([
            'success' => 'Lost Pet Report updated successfully.',
        ], 200);
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
