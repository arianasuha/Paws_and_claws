<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRegisterRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Notification;

class AppointmentController extends Controller
{

    /**
     * @OA\Get(
     * path="/api/appointments",
     * operationId="getAppointmentsList",
     * tags={"Appointments"},
     * summary="Get a list of appointments for the authenticated user",
     * description="Returns a list of appointments. Vets and service providers can see all appointments they are associated with, while regular users can only see their own.",
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="appointments",
     * type="array",
     * @OA\Items(ref="#/components/schemas/Appointment")
     * )
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'vet' || $user->role === 'service_provider') {
            $appointments = Appointment::where('provider_id', $user->id)->get();
        } else {
            $appointments = Appointment::where('user_id', $user->id)->get();
        }

        return response()->json(['appointments' => $appointments]);
    }


    /**
     * @OA\Post(
     * path="/api/appointments",
     * operationId="createAppointment",
     * tags={"Appointments"},
     * summary="Create a new appointment",
     * description="Creates a new appointment for the authenticated user.",
     * security={{"sanctum":{}}},
     * @OA\RequestBody(
     * required=true,
     * description="Appointment data",
     * @OA\JsonContent(ref="#/components/schemas/AppointmentRegisterRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Appointment created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Appointment created successfully."),
     * @OA\Property(property="appointment", ref="#/components/schemas/Appointment")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
     */
    public function store(AppointmentRegisterRequest $request)
    {
        $user = Auth::user();

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'provider_id' => $request->provider_id,
            'pet_id' => $request->pet_id,
            'app_date' => $request->app_date,
            'app_time' => $request->app_time,
            'visit_reason' => $request->visit_reason,
            'status' => 'pending',
        ]);

        Notification::create([
            'user_id' => $appointment->user_id,
            'subject' => 'Appointment Created',
            'message' => 'Your appointment has been created.',
        ]);

        Notification::create([
            'user_id' => $appointment->provider_id,
            'subject' => 'New Appointment',
            'message' => 'You have a new appointment.',
        ]);


        return response()->json(['message' => 'Appointment created successfully.', 'appointment' => $appointment], 201);
    }


    /**
     * @OA\Get(
     * path="/api/appointments/{appointment}",
     * operationId="getAppointmentById",
     * tags={"Appointments"},
     * summary="Get a single appointment by ID",
     * description="Returns a single appointment by its ID. Accessible by the appointment's owner or a vet/service provider.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="appointment",
     * description="ID of the appointment",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(property="appointment", ref="#/components/schemas/Appointment")
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized to view this appointment."
     * ),
     * @OA\Response(
     * response=404,
     * description="Appointment not found"
     * )
     * )
     */
    public function show(Appointment $appointment)
    {

        $user = Auth::user();

        if ($appointment->user_id !== $user->id && $appointment->provider_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to view this appointment.'], 403);
        }

        return response()->json(['appointment' => $appointment]);
    }


    /**
     * @OA\Patch(
     * path="/api/appointments/{appointment}",
     * operationId="updateAppointment",
     * tags={"Appointments"},
     * summary="Update an existing appointment",
     * description="Updates an appointment. Only the appointment's owner can update it.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="appointment",
     * description="ID of the appointment to update",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Appointment update data",
     * @OA\JsonContent(ref="#/components/schemas/AppointmentUpdateRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Appointment updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Appointment updated successfully."),
     * @OA\Property(property="appointment", ref="#/components/schemas/Appointment")
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized to update this appointment."
     * ),
     * @OA\Response(
     * response=404,
     * description="Appointment not found"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     */
    public function update(AppointmentUpdateRequest $request, Appointment $appointment)
    {
        $user = Auth::user();
        if ($appointment->provider_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to update this appointment.'], 403);
        }

        $appointment->update($request->validated());
        Notification::create([
            'user_id' => $appointment->user_id,
            'subject' => 'Appointment Updated',
            'message' => 'Your appointment has been updated.',
        ]);

        return response()->json(['message' => 'Appointment updated successfully.', 'appointment' => $appointment]);
    }

    /**
     * @OA\Delete(
     * path="/api/appointments/{appointment}",
     * operationId="deleteAppointment",
     * tags={"Appointments"},
     * summary="Delete an appointment",
     * description="Deletes an appointment. Only the appointment's owner can delete it.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="appointment",
     * description="ID of the appointment to delete",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Appointment deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Appointment deleted successfully.")
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized to delete this appointment."
     * ),
     * @OA\Response(
     * response=404,
     * description="Appointment not found"
     * )
     * )
     */
    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();
        if ($appointment->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to delete this appointment.'], 403);
        }

        $appointment->delete();

        return response()->json(null, 204);
    }
}
