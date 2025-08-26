<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyShelter\EmergencyShelterRegisterRequest;
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

            // Create the new emergency shelter request
            $emergencyShelter = EmergencyShelter::create($validatedData);

            DB::commit();

            return response()->json([
                "message" => "Emergency pet shelter request created successfully.",
                "shelter_request" => $emergencyShelter
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing emergency shelter request: ' . $e->getMessage());
            return response()->json(["errors" => "Failed to create emergency shelter request."], 500);
        }
    }

    /**
     * Display a list of all emergency pet shelter requests for the authenticated user.
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
     * @param  string  $shelterId
     * @return JsonResponse
     */
    public function show(string $shelterId): JsonResponse
    {
        try {
            $shelterRequest = EmergencyShelter::where('shelter_id', $shelterId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

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

            return response()->json(['message' => 'Emergency shelter request deleted successfully.'], 200);

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
