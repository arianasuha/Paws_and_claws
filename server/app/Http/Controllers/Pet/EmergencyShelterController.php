<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\EmergencyShelterRegisterRequest;
use App\Models\EmergencyShelter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmergencyShelterController extends Controller
{
    /**
     * Store a newly created emergency pet shelter request.
     *
     * @OA\Post(
     * path="/api/shelters",
     * operationId="createShelterRequest",
     * tags={"Emergency Shelters"},
     * summary="Create a new emergency pet shelter request",
     * description="Creates a new emergency shelter request for a pet. The request is associated with the authenticated user.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Request payload for creating a new emergency shelter request",
     * @OA\JsonContent(ref="#/components/schemas/EmergencyShelterRegisterRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Emergency pet shelter request created successfully.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Emergency pet shelter request created successfully."),
     * @OA\Property(property="shelter_request", ref="#/components/schemas/EmergencyShelter")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation Error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     *
     * @param  EmergencyShelterRegisterRequest  $request
     * @return JsonResponse
     */
    public function store(EmergencyShelterRegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validated();

            // Assign the authenticated user's ID
            $validatedData['user_id'] = Auth::id();



            $emergencyShelter = EmergencyShelter::create($validatedData);

            $emergencyShelter->load(['user', 'pet']);

            DB::commit();

            return response()->json([
                "success" => "Emergency pet shelter request created successfully."],
                 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing emergency shelter request: ' . $e->getMessage());
            return response()->json(["errors" => "Failed to create emergency shelter request."], 500);
        }
    }

    /**
     * Display a list of all emergency pet shelter requests for the authenticated user.
     *
     * @OA\Get(
     * path="/api/shelters",
     * operationId="getShelterRequests",
     * tags={"Emergency Shelters"},
     * summary="Get all emergency pet shelter requests for the authenticated user",
     * description="Retrieves a list of all emergency shelter requests submitted by the currently authenticated user.",
     * security={{"sanctum": {}}},
     * @OA\Response(
     * response=200,
     * description="A list of emergency shelter requests.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/EmergencyShelter")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $shelterRequests = EmergencyShelter::where('user_id', Auth::id())
                ->get();

            return response()->json($shelterRequests, 200);

        } catch (\Exception $e) {
            Log::error('Error fetching emergency shelter requests: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch emergency shelter requests.'], 500);
        }
    }

    /**
     * Display the specified emergency pet shelter request.
     *
     * @OA\Get(
     * path="/api/shelters/{shelterId}",
     * operationId="showShelterRequest",
     * tags={"Emergency Shelters"},
     * summary="Get a specific emergency pet shelter request",
     * description="Retrieves a single emergency shelter request by its ID, for the authenticated user.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="shelterId",
     * in="path",
     * required=true,
     * description="ID of the emergency shelter request",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Emergency shelter request details.",
     * @OA\JsonContent(ref="#/components/schemas/EmergencyShelter")
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     *
     * @param  string  $shelterId
     * @return JsonResponse
     */
    public function show(string $shelterId): JsonResponse
    {
        try {
            $shelterRequest = EmergencyShelter::with([
                'user' => function ($query) {
                    $query->select('id', 'username', 'email');
                },
                'pet'
            ])->find($shelterId);

            if (!$shelterRequest) {
                return response()->json(['error' => 'Emergency shelter request not found or unauthorized.'], 404);
            }

            return response()->json($shelterRequest, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Emergency shelter request not found or unauthorized.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching emergency shelter request: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch emergency shelter request.'], 500);
        }
    }

    /**
     * Remove the specified emergency pet shelter request.
     *
     * @OA\Delete(
     * path="/api/shelters/{shelterId}",
     * operationId="deleteShelterRequest",
     * tags={"Emergency Shelters"},
     * summary="Delete an emergency pet shelter request",
     * description="Deletes an emergency shelter request by its ID, if the request belongs to the authenticated user.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="shelterId",
     * in="path",
     * required=true,
     * description="ID of the emergency shelter request to delete",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=204,
     * description="Emergency shelter request deleted successfully."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     *
     * @param  string  $shelterId
     * @return JsonResponse
     */
    public function destroy(string $shelterId): JsonResponse
    {
        DB::beginTransaction();

        try {
            $shelterRequest = EmergencyShelter::where('shelter_id', $shelterId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $shelterRequest->delete();

            DB::commit();

            return response()->json(null, 204);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['error' => 'Emergency shelter request not found or unauthorized.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting emergency shelter request: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete emergency shelter request.'], 500);
        }
    }
}
