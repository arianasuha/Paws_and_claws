<?php

namespace App\Http\Controllers\Vet;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Vet\AppointmentRegisterRequest;
use App\Http\Requests\Vet\AppointmentUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class AppointmentController extends Controller
{
    /**
     * Set middleware to protect all methods except show and index.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * @OA\Get(
     * path="/api/appointments",
     * operationId="indexAppointments",
     * tags={"Appointments"},
     * summary="Get a list of all appointments",
     * description="Returns a paginated list of appointments.",
     * security={{"sanctum": {}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Appointment")
     * )
     * ),
     * @OA\Response(response=401, description="Unauthenticated"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            // Eager-load the related 'pet' and 'vet' relationships and paginate the results
            $appointments = Appointment::with(['pet', 'vet'])->paginate(10);
            return response()->json($appointments, 200);
        } catch (\Exception $e) {
            return response()->json(["errors" => "Failed to retrieve appointments."], 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/appointments",
     * operationId="storeAppointment",
     * tags={"Appointments"},
     * summary="Create a new appointment",
     * description="Creates a new appointment for a pet with a specific vet. Requires authentication.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/StoreAppointmentRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Appointment created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string", example="Appointment created successfully."),
     * @OA\Property(property="data", ref="#/components/schemas/Appointment")
     * )
     * ),
     * @OA\Response(response=401, description="Unauthenticated"),
     * @OA\Response(response=403, description="Forbidden"),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function store(AppointmentRegisterRequest $request): JsonResponse
    {
        try {
            $appointment = Appointment::create($request->validated());
            return response()->json([
                "success" => "Appointment created successfully.",
                "data" => $appointment
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json(["errors" => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(["errors" => "Failed to create appointment."], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/appointments/{appointment}",
     * operationId="showAppointment",
     * tags={"Appointments"},
     * summary="Get a single appointment by ID",
     * description="Returns a single appointment by its ID.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="appointment",
     * in="path",
     * required=true,
     * description="ID of the appointment to retrieve",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Appointment")
     * ),
     * @OA\Response(response=401, description="Unauthenticated"),
     * @OA\Response(response=404, description="Appointment not found"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function show(Appointment $appointment): JsonResponse
    {
        try {
            $appointment->load(['pet', 'vet']);
            return response()->json($appointment, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["errors" => "Appointment not found."], 404);
        } catch (\Exception $e) {
            return response()->json(["errors" => "Failed to retrieve appointment."], 500);
        }
    }

    /**
     * @OA\Put(
     * path="/api/appointments/{appointment}",
     * operationId="updateAppointment",
     * tags={"Appointments"},
     * summary="Update an existing appointment",
     * description="Updates an appointment. Requires the user to be the owner of the pet associated with the appointment.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="appointment",
     * in="path",
     * required=true,
     * description="ID of the appointment to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/StoreAppointmentRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Appointment updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string", example="Appointment updated successfully.")
     * )
     * ),
     * @OA\Response(response=401, description="Unauthenticated"),
     * @OA\Response(response=403, description="Forbidden"),
     * @OA\Response(response=404, description="Appointment not found"),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function update(AppointmentUpdateRequest $request, Appointment $appointment): JsonResponse
    {
        try {
            // Check if the authenticated user is the pet's owner before updating
            if (Auth::id() !== $appointment->pet->user_id) {
                return response()->json(["errors" => "You are not authorized to update this appointment."], 403);
            }

            $appointment->update($request->validated());
            return response()->json(["success" => "Appointment updated successfully."], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["errors" => "Appointment not found."], 404);
        } catch (\Exception $e) {
            return response()->json(["errors" => "Failed to update appointment."], 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/appointments/{appointment}",
     * operationId="destroyAppointment",
     * tags={"Appointments"},
     * summary="Delete an appointment",
     * description="Deletes an appointment. Requires the user to be the owner of the pet associated with the appointment.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="appointment",
     * in="path",
     * required=true,
     * description="ID of the appointment to delete",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(response=204, description="Appointment deleted successfully"),
     * @OA\Response(response=401, description="Unauthenticated"),
     * @OA\Response(response=403, description="Forbidden"),
     * @OA\Response(response=404, description="Appointment not found"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function destroy(Appointment $appointment): JsonResponse
    {
        try {
            // Check if the authenticated user is the pet's owner before deleting
            if (Auth::id() !== $appointment->pet->user_id) {
                return response()->json(["errors" => "You are not authorized to delete this appointment."], 403);
            }

            $appointment->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(["errors" => "Appointment not found."], 404);
        } catch (\Exception $e) {
            return response()->json(["errors" => "Failed to delete appointment."], 500);
        }
    }
}