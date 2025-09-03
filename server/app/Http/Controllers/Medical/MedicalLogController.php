<?php

namespace App\Http\Controllers\Medical;

use App\Http\Requests\Medical\MedicalLogUpdateRequest;
use App\Models\MedicalLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Medical\MedicalLogRegisterRequest;
use App\Jobs\ScheduleMedicalReminders; // We'll need to create this job
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pet;
use App\Models\MedicalReminder; // Assuming you have this model
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class MedicalLogController extends Controller
{
    /**
     * Display a listing of medical logs for a specific pet with filtering and sorting.
     *
     * @OA\Get(
     * path="/api/medicalpet-logs/{petId}",
     * operationId="getMedicalLogsByPet",
     * tags={"Medical Logs"},
     * summary="Get and filter medical logs for a specific pet",
     * description="Returns a chronological list of medical logs for a pet, with optional filtering by date range, diagnosis, or keyword.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="petId",
     * description="ID of the pet",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Parameter(
     * name="diagnosis",
     * description="Filter by diagnosis keyword",
     * in="query",
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="page",
     * description="Page number for pagination (if enabled)",
     * in="query",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(
     * property="medical_logs",
     * type="array",
     * @OA\Items(ref="#/components/schemas/MedicalLog")
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=404, description="Pet not found", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    public function index(Request $request, $petId)
    {
        try {
            $pet = Pet::where('id', $petId)->first();

            if (!$pet) {
                return response()->json(['errors' => 'Pet not found.'], 404);
            }

            if (Auth::user()->id !== $pet->user_id) {
                return response()->json(['errors' => 'You are unauthorized to see this information.'], 403);
            }

            $query = $pet->medicalLogs();

            if ($request->has('diagnosis')) {
                $query->where('diagnosis', 'ilike', '%' . $request->diagnosis . '%');
            }

            $medicalLogs = $query->orderBy('visit_date', 'desc')->paginate(10);

            return response()->json(['medical_logs' => $medicalLogs]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['errors' => 'You are unauthorized to see this information.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching medical logs: ' . $e->getMessage());
            return response()->json(['errors' => 'Could not fetch medical logs.'], 500);
        }
    }


    /**
     * Store a newly created medical log for a pet.
     *
     * @OA\Post(
     * path="/api/medical-logs",
     * operationId="storeMedicalLog",
     * tags={"Medical Logs"},
     * summary="Create a new medical log",
     * description="Creates a new medical log and associates it with a pet.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/MedicalLogRegisterRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Medical log created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Medical log created successfully."),
     * @OA\Property(property="medical_log", type="object", ref="#/components/schemas/MedicalLog")
     * )
     * ),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=500, description="Internal server error", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    public function store(MedicalLogRegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            // Find the pet and ensure it belongs to the authenticated user.
            // firstOrFail() will throw a ModelNotFoundException if the pet is not found or is unauthorized.
            $pet = Pet::where('id', $request->pet_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$pet) {
                DB::rollBack();
                return response()->json(['errors' => 'Pet not found or unauthorized.'], 404);
            }

            $validatedData = $request->validated();

            $validatedData['treatment_prescribed'] = $request->input('prescribed_medication', 'N/A');

            $medicalLog = MedicalLog::create($validatedData);

            $pet->medicalLogs()->attach($medicalLog->id);

            DB::commit();

            return response()->json([
                "success" => "Medical log created successfully."
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => 'Pet not found or unauthorized.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing medical log: ' . $e->getMessage());
            return response()->json(['errors' => 'Could not store medical log.'], 500);
        }
    }
    /**
     * Display the specified medical log.
     *
     * @OA\Get(
     * path="/api/medical-logs/{medicalLog}",
     * operationId="showMedicalLog",
     * tags={"Medical Logs"},
     * summary="Get a single medical log",
     * description="Returns a single medical log by ID, accessible only by the pet's owner.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="medicalLog",
     * description="ID of the medical log",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/MedicalLog")
     * ),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=404, description="Medical log not found", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    public function show(string $medicalLogId): JsonResponse
    {
        try {
            $medicalLog = MedicalLog::find($medicalLogId);

            if (!$medicalLog) {
                return response()->json(['errors' => 'Medical log not found.'], 404);
            }

            $isAuthorized = $medicalLog->pets()->where('user_id', Auth::id())->exists();

            if (!$isAuthorized) {
                return response()->json(['errors' => 'You are unauthorized to view this medical log.'], 403);
            }

            $medicalLog->load(['pets' => function ($query) {
                $query->select('pets.id', 'pets.name');
            }]);

            return response()->json($medicalLog, 200);

        } catch (\Exception $e) {
            Log::error('Error fetching medical log: ' . $e->getMessage());
            return response()->json(['errors' => 'Could not fetch medical log.'], 500);
        }
    }

    /**
     * Update the specified medical log.
     *
     * @OA\Patch(
     * path="/api/medical-logs/{medicalLog}",
     * operationId="updateMedicalLog",
     * tags={"Medical Logs"},
     * summary="Update a medical log",
     * description="Updates an existing medical log.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="medicalLog",
     * description="ID of the medical log to update",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/MedicalLogUpdateRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Medical log updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Medical log updated successfully."),
     * @OA\Property(property="medical_log", type="object", ref="#/components/schemas/MedicalLog")
     * )
     * ),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=404, description="Medical log not found", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    public function update(MedicalLogUpdateRequest $request, string $medicalLogId)
    {
        try {
            $medicalLog = MedicalLog::find($medicalLogId);

            if (!$medicalLog) {
                return response()->json(['errors' => 'Medical log not found.'], 404);
            }

            $isAuthorized = $medicalLog->pets()->where('user_id', Auth::id())->exists();

            if (!$isAuthorized) {
                return response()->json(['errors' => 'You are unauthorized to update this medical log.'], 403);
            }

            // Update the medical log with the new data
            $validated = $request->validated();
            $medicalLog->update($validated);

            return response()->json([
                'success' => 'Pet Medical Log information updated successfully.'],
                 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified medical log.
     *
     * @OA\Delete(
     * path="/api/medical-logs/{medicalLog}",
     * operationId="deleteMedicalLog",
     * tags={"Medical Logs"},
     * summary="Delete a medical log",
     * description="Deletes a medical log by ID, accessible only by the pet's owner.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="medicalLog",
     * description="ID of the medical log to delete",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=200,
     * description="Medical log deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Medical log deleted successfully!")
     * )
     * ),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/ErrorResponse")),
     * @OA\Response(response=404, description="Medical log not found", @OA\JsonContent(ref="#/components/schemas/ErrorResponse"))
     * )
     */
    public function destroy(MedicalLog $medicalLog)
    {
        try {
            $isAuthorized = $medicalLog->pets()->where('user_id', Auth::id())->exists();

            if (!$isAuthorized) {
                return response()->json(['errors' => 'You are unauthorized to delete this medical log.'], 403);
            }

            $medicalLog->pets()->detach(Auth::id());


            $medicalLog->delete();


            return response()->json(null, 204);

        } catch (\Exception $e) {
            Log::error('Error deleting medical log: ' . $e->getMessage());
            return response()->json(['errors' => 'Could not delete medical log.'], 500);
        }
    }
}
