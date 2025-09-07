<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\EmergencyShelterRegisterRequest;
use App\Models\EmergencyShelter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Models\Pet;


class EmergencyShelterController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/shelters",
     * operationId="createEmergencyShelterRequest",
     * tags={"Emergency Shelters"},
     * summary="Create a new emergency pet shelter request",
     * description="Creates a new emergency shelter request for the authenticated user's pet. The system validates that the pet belongs to the user before creating the request.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * description="Request payload for creating a new emergency shelter request",
     * @OA\JsonContent(
     * required={"pet_id", "request_date"},
     * @OA\Property(property="pet_id", type="integer", description="ID of the pet needing shelter"),
     * @OA\Property(property="request_date", type="string", format="date", description="Date of the placing request for emergency shelter"),
     * )
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
     * description="Validation Error or Unauthorized Pet Access",
     * @OA\JsonContent(
     * @OA\Property(property="errors", type="object", example={"pet_id": {"The selected pet does not belong to your account."}})
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
     */
    public function store(EmergencyShelterRegisterRequest $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $requestDate = $request->request_date;

            $pet = Pet::where('id', $request->pet_id)
                ->where('user_id', $userId)
                ->first();

            if (!$pet) {
                return response()->json([
                    'errors' => "You are unauthorized to place an emergency shelter request for this pet."
                ], 403);
            }

            $existingRequest = EmergencyShelter::where('user_id', $userId)
                ->where('request_date', $requestDate)
                ->first();

            if ($existingRequest) {
                return response()->json([
                    'errors' => "You have already submitted an emergency shelter request on this date."
                ], 409);
            }
            $emergencyShelter = EmergencyShelter::create([
                'user_id' => $userId,
                'pet_id' => $request->pet_id,
                'request_date' => $request->request_date,
            ]);

            $emergencyShelter->load('pet', 'user');

            Notification::create([
                'user_id' => Auth::user()->id,
                'subject' => "Emergency Request",
                'message' => "Your request has been placed successfully",
            ]);

            return response()->json(
                [
                    "success" => "Emergency pet shelter request created successfully."
                ],
                201
            );

        } catch (\Exception $e) {
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
     * @OA\Parameter(
     * name="page",
     * description="Page number for pagination (if enabled)",
     * in="query",
     * @OA\Schema(type="integer")
     * ),
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
            if (!Auth::user()->is_admin) {
                return response()->json([
                    "errors" => "You are not authorized to view all the requests"
                ], 403);
            }

            $shelterRequests = EmergencyShelter::query()
                ->with([
                    'pet' => function ($query) {
                        $query->select('id', 'name', 'user_id');
                    },
                    'user' => function ($query) {
                        $query->select('id', 'username');
                    }
                ])
                // Order the results by the 'request_date' in descending order
                ->orderByDesc('request_date')
                ->paginate();

            return response()->json($shelterRequests, 200);

        } catch (\Exception $e) {
            Log::error('Error fetching emergency shelter requests: ' . $e->getMessage());
            return response()->json(['errors' => 'Could not fetch emergency shelter requests.'], 500);
        }
    }

    /**
     * Display the specified emergency pet shelter request.
     *
     * @OA\Get(
     * path="/api/shelterspet/{shelterId}",
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
            $user = Auth::user();

            if (!$user) {
                return response()->json(['errors' => 'You must be logged in to view this request.'], 401);
            }

            $shelterRequest = EmergencyShelter::with([
                'user' => function ($query) {
                    $query->select('id', 'username', 'email');
                },
                'pet'
            ])
                ->where('id', $shelterId)
                ->where('user_id', $user->id)
                ->first();

            if (!$shelterRequest) {
                return response()->json(['errors' => 'You are not authorized to view this shelter request.'], 404);
            }

            return response()->json($shelterRequest, 200);

        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
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

        try {
            $shelterRequest = EmergencyShelter::where('id', $shelterId)->first();


            if (!$shelterRequest) {
                return response()->json(['errors' => 'Emergency Shelter Request not found.'], 404);
            }

            if (!Auth::user()->is_admin) {
                return response()->json(['errors' => 'You are not authorized to delete this shelter request.'], 403);
            }


            $shelterRequest->delete();


            return response()->json(null, 204);

        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
