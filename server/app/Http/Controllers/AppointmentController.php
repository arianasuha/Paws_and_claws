<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRegisterRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments for the authenticated user.
     * The appointments shown depend on the user's role.
     */
    public function index()
    {
        $user = Auth::user();

        // Check the user's role to determine which appointments they can view.
        if ($user->role === 'vet' || $user->role === 'service_provider') {
            // Vets and service providers can see all appointments.
            $appointments = Appointment::all();
        } else {
            // Regular users can only see their own appointments.
            $appointments = Appointment::where('user_id', $user->id)->get();
        }

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Store a newly created appointment in the database.
     */
    public function store(AppointmentRegisterRequest $request)
    {
        // Get the currently authenticated user.
        $user = Auth::user();

        // Determine the user's role and create the appointment.
        $appointment = new Appointment([
            'pet_id' => $request->pet_id,
            'app_date' => $request->app_date,
            'app_time' => $request->app_time,
            'visit_reason' => $request->visit_reason,
            'status' => 'pending', // Default status for a new appointment
        ]);

        // 🚨 IMPORTANT: The user_id is assigned here from the authenticated user.
        // It is NOT taken from the user's request, which is a key security practice.
        if ($user->role === 'vet' || $user->role === 'service_provider') {
            $appointment->user_id = $user->id;
        } else {
            // This is for a standard user creating an appointment for their pet.
            $appointment->user_id = $user->id;
        }

        // Save the appointment to the database.
        $appointment->save();

        return response()->json(['message' => 'Appointment created successfully.', 'appointment' => $appointment], 201);
    }

    /**
     * Display a single appointment.
     */
    public function show(Appointment $appointment)
    {
        // We can use route model binding to automatically find the appointment.
        // We also check if the authenticated user is authorized to view this appointment.
        $user = Auth::user();

        // Check if the user is the owner of the appointment or a vet/service provider.
        if ($appointment->user_id !== $user->id && $user->role !== 'vet' && $user->role !== 'service_provider') {
            return response()->json(['message' => 'Unauthorized to view this appointment.'], 403);
        }

        return response()->json(['appointment' => $appointment]);
    }

    /**
     * Update an existing appointment.
     */
    public function update(AppointmentUpdateRequest $request, Appointment $appointment)
    {
        // Check for authorization first. Only the user who created it, or an admin can edit it.
        $user = Auth::user();
        if ($appointment->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to update this appointment.'], 403);
        }

        // Update the appointment.
        $appointment->update($request->validated());

        return response()->json(['message' => 'Appointment updated successfully.', 'appointment' => $appointment]);
    }

    /**
     * Delete an appointment.
     */
    public function destroy(Appointment $appointment)
    {
        $user = Auth::user();
        if ($appointment->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to delete this appointment.'], 403);
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully.']);
    }
}
