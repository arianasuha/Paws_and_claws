<?php

namespace App\Http\Controllers\Medical;

use App\Models\MedicalLog;
use App\Models\PetMedical;
use App\Http\Controllers\Controller;
use App\Http\Requests\Medical\MedicalLogRegisterRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class MedicalLogController extends Controller
{
    /**
     * Display a listing of medical logs for a specific pet.
     *
     * @OA\Get(
     * path="/api/medical-logs/{petId}",
     * operationId="getMedicalLogsByPet",
     * tags={"Medical Logs"},
     * summary="Get medical logs for a specific pet",
     * description="Returns a list of all medical logs associated with the specified pet, accessible only by the pet's owner.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="petId",
     * description="ID of the pet",
     * required=true,
     * in="path",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/MedicalLog")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Pet not found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function index($petId)
    {
        try {
            $pet = Pet::where('id', $petId)->where('user_id', Auth::id())->first();

            if (!$pet) {
                return response()->json(['error' => 'Pet not found or unauthorized.'], 404);
            }

            $medicalLogs = $pet->medicalLogs()->get();

            return response()->json(['medical_logs' => $medicalLogs]);

        } catch (\Exception $e) {
            Log::error('Error fetching medical logs: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch medical logs.'], 500);
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
     * @OA\JsonContent(ref="#/components/schemas/MedicalLogRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Medical log created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string", example="Medical log created successfully.")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal server error",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function store(MedicalLogRegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            $medicalLog = MedicalLog::create($request->validated());

            $petId = $request->input('pet_id');

            PetMedical::create([
                'pet_id' => $petId,
                'medical_id' => $medicalLog->id
            ]);

            DB::commit();

            return response()->json([
                "success" => "Medical log created successfully.",
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing medical log: ' . $e->getMessage());
            return response()->json(['error' => 'Could not store medical log.'], 500);
        }
    }

    /**
     * Display the specified medical log.
     *
     * @OA\Get(
     * path="/api/medical-logs/show/{medicalLog}",
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
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/MedicalLog")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Medical log not found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function show($medicalLog)
    {
        try {
            $isAuthorized = $medicalLog->pets()->where('user_id', Auth::id())->exists();

            if (!$isAuthorized) {
                return response()->json(['error' => 'Unauthorized to view this medical log.'], 403);
            }

            $medicalLog->load('pets');

            return response()->json($medicalLog, 200);

        } catch (\Exception $e) {
            Log::error('Error fetching medical log: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch medical log.'], 500);
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
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Medical log deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Medical log deleted successfully!")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     * @OA\Response(
     * response=404,
     * description="Medical log not found",
     * @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * )
     * )
     */
    public function destroy($medicalLog)
    {
        try {
            $isAuthorized = $medicalLog->pets()->where('user_id', Auth::id())->exists();

            if (!$isAuthorized) {
                return response()->json(['error' => 'Unauthorized to delete this medical log.'], 403);
            }

            $medicalLog->delete();
            return response()->json(['message' => 'Medical log deleted successfully!']);

        } catch (\Exception $e) {
            Log::error('Error deleting medical log: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete medical log.'], 500);
        }
    }
}


<?php

namespace App\Http\Controllers\Medical;

use App\Models\MedicalLog;
use App\Models\PetMedical;
use App\Http\Controllers\Controller;
use App\Http\Requests\Medical\MedicalLogRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class MedicalLogController extends Controller
{
    /**
     * Display a listing of medical logs for a specific pet with filtering and sorting.
     *
     * @OA\Get(
     * path="/api/medical-logs/{petId}",
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
     * name="start_date",
     * description="Start date for filtering (YYYY-MM-DD)",
     * in="query",
     * @OA\Schema(type="string", format="date")
     * ),
     * @OA\Parameter(
     * name="end_date",
     * description="End date for filtering (YYYY-MM-DD)",
     * in="query",
     * @OA\Schema(type="string", format="date")
     * ),
     * @OA\Parameter(
     * name="diagnosis",
     * description="Filter by diagnosis keyword",
     * in="query",
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="keyword",
     * description="Search for a keyword in notes, vet name, or clinic name",
     * in="query",
     * @OA\Schema(type="string")
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
            // Find the pet and ensure it belongs to the authenticated user
            $pet = Pet::where('id', $petId)->where('user_id', Auth::id())->firstOrFail();

            // Start a query on the pet's medical logs
            $query = $pet->medicalLogs();

            // Apply filters if they are present in the request
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('visit_date', [$request->start_date, $request->end_date]);
            }

            if ($request->has('diagnosis')) {
                $query->where('diagnosis', 'like', '%' . $request->diagnosis . '%');
            }

            if ($request->has('keyword')) {
                $keyword = $request->keyword;
                $query->where(function ($q) use ($keyword) {
                    $q->where('notes', 'like', '%' . $keyword . '%')
                      ->orWhere('vet_name', 'like', '%' . $keyword . '%')
                      ->orWhere('clinic_name', 'like', '%' . $keyword . '%');
                });
            }

            // Order records chronologically by visit date
            $medicalLogs = $query->orderBy('visit_date', 'desc')->get();

            return response()->json(['medical_logs' => $medicalLogs]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Pet not found or unauthorized.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching medical logs: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch medical logs.'], 500);
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
     * description="Creates a new medical log and associates it with a pet. Requires `pet_id` in the request body.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/MedicalLogRequest")
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
            $pet = Pet::where('id', $request->pet_id)->where('user_id', Auth::id())->firstOrFail();
            $medicalLog = MedicalLog::create($request->validated());

            PetMedical::create([
                'pet_id' => $pet->id,
                'medical_id' => $medicalLog->id
            ]);

            DB::commit();

            return response()->json([
                "message" => "Medical log created successfully.",
                "medical_log" => $medicalLog
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['error' => 'Pet not found or unauthorized.'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing medical log: ' . $e->getMessage());
            return response()->json(['error' => 'Could not store medical log.'], 500);
        }
    }

    /**
     * Display the specified medical log.
     *
     * @OA\Get(
     * path="/api/medical-logs/show/{medicalLog}",
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
    public function show(MedicalLog $medicalLog)
    {
        try {
            $isAuthorized = $medicalLog->pets()->where('user_id', Auth::id())->exists();

            if (!$isAuthorized) {
                return response()->json(['error' => 'Unauthorized to view this medical log.'], 403);
            }

            // Load the pet relationship for the medical log
            $medicalLog->load('pets');

            return response()->json($medicalLog, 200);

        } catch (\Exception $e) {
            Log::error('Error fetching medical log: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch medical log.'], 500);
        }
    }

    /**
     * Update the specified medical log.
     *
     * @OA\Put(
     * path="/api/medical-logs/{medicalLog}",
     * operationId="updateMedicalLog",
     * tags={"Medical Logs"},
     * summary="Update a medical log",
     * description="Updates an existing medical log. Requires `pet_id` in the request body.",
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
     * @OA\JsonContent(ref="#/components/schemas/MedicalLogRequest")
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
    public function update(Request $request, MedicalLog $medicalLog)
    {
        try {
            // First, check if the user is authorized to update this log.
            $isAuthorized = $medicalLog->pets()->where('user_id', Auth::id())->exists();

            if (!$isAuthorized) {
                return response()->json(['error' => 'Unauthorized to update this medical log.'], 403);
            }

            // Update the medical log with the new data
            $medicalLog->update($request->all());

            return response()->json([
                "message" => "Medical log updated successfully.",
                "medical_log" => $medicalLog
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating medical log: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update medical log.'], 500);
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
            // First, check if the user is authorized to delete this log.
            $isAuthorized = $medicalLog->pets()->where('user_id', Auth::id())->exists();

            if (!$isAuthorized) {
                return response()->json(['error' => 'Unauthorized to delete this medical log.'], 403);
            }

            // If a single medical log can be associated with multiple pets,
            // we should only detach the pet's association. If it's a 1-to-1 relationship,
            // then a hard delete is fine. Assuming a many-to-many relationship,
            // we delete the pivot table entry first.
            $medicalLog->pets()->detach(Auth::id());

            // Check if the medical log is now orphaned (not linked to any pet) before deleting it completely
            if ($medicalLog->pets()->count() === 0) {
                 $medicalLog->delete();
            }

            return response()->json(['message' => 'Medical log deleted successfully!']);

        } catch (\Exception $e) {
            Log::error('Error deleting medical log: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete medical log.'], 500);
        }
    }
}
