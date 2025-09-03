<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRegisterRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;
use App\Models\Pet;

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
     * @OA\Parameter(
     * name="page",
     * in="query",
     * description="Page number for pagination",
     * required=false,
     * @OA\Schema(type="integer", default=1)
     * ),
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

        if ($user->isAdmin()) {
            $appointments = Appointment::orderByDesc('app_date')->orderByDesc('app_time')->paginate(10);
        } elseif ($user->isVet() || $user->isServiceProvider()) {
            $appointments = Appointment::where('provider_id', $user->id)->orderByDesc('app_date')->orderByDesc('app_time')->paginate(10);
        } else {
            $appointments = Appointment::where('user_id', $user->id)->orderByDesc('app_date')->orderByDesc('app_time')->paginate(10);
        }

        return response()->json($appointments, 200);
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

        $validated = $request->validated();

        $pet = Pet::find($validated['pet_id']);

        if ($pet->user_id !== $user->id) {
            return response()->json([
                'errors' => 'You are not authorized to create an appointment for this pet.'
            ], 403);
        }

        $validated['user_id'] = $user->id;
        $appointment = Appointment::create($validated);

        $provider = User::find($validated['provider_id']);

        Notification::create([
            'user_id' => $appointment->user_id,
            'subject' => 'Appointment Created',
            'message' => 'Your appointment has been created with.'.$provider->username,
        ]);


        Notification::create([
            'user_id' => $appointment->provider_id,
            'subject' => 'New Appointment',
            'message' => 'You have a new appointment.',
        ]);


        return response()->json([
            'success' => 'Appointment created successfully.'
        ], 201);
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
    public function show(string $appointment)
    {

        $user = Auth::user();

        $appointment = Appointment::where('id', $appointment)
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                },
                'provider' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                },
                'pet' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->first();

        if (!$appointment) {
            return response()->json(['errors' => 'Appointment not found.'], 404);
        }

        if ($appointment->user_id !== $user->id && $appointment->provider_id !== $user->id) {
            return response()->json([
                'errors' => 'Unauthorized to view this appointment.'
            ], 403);
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
    public function update(AppointmentUpdateRequest $request, string $appointment)
    {
        $user = Auth::user();

        $validated = $request->validated();
        $appointment = Appointment::where('id', $appointment)->first();

        if (!$appointment) {
            return response()->json([
                'errors' => 'Appointment not found.'
            ], 404);
        }

        if (
            ($appointment->user_id === $user->id && $validated['status'] !== 'canceled') ||
            ($appointment->provider_id === $user->id && $validated['status'] === 'canceled')
        ) {
            return response()->json([
                'errors' => 'You cannot update this status'
            ], 403);
        }

        $appointment->update($validated);

        Notification::create([
            'user_id' => $appointment->user_id,
            'subject' => 'Appointment Updated',
            'message' => 'Your appointment has been updated.',
        ]);

        return response()->json([
            'success' => 'Appointment updated successfully.', 
        ]);
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
    public function destroy(string $appointment)
    {
        $appointment = Appointment::where('id', $appointment)->first();

        if (!$appointment) {
            return response()->json([
                'errors' => 'Appointment not found.'
            ], 404);
        }

        if (!Auth::user()->is_admin) {
            return response()->json([
                'errors' => 'Unauthorized to delete this appointment.'
            ], 403);
        }

        $appointment->delete();

        return response()->json(null, 204);
    }
}
