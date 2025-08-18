<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Http\Requests\Pet\PetReminderRegisterRequest;
use App\Http\Requests\Pet\PetReminderUpdateRequest;
use Illuminate\Http\JsonResponse;


class PetReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Get all reminders for the authenticated user's pets
        $reminders = Reminder::whereHas('pet', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        return response()->json($reminders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PetReminderRegisterRequest  $request
     * @return JsonResponse
     */
    public function store(PetReminderRegisterRequest $request): JsonResponse
    {
        $reminder = Reminder::create($request->validated());

        return response()->json($reminder, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Reminder  $reminder
     * @return JsonResponse
     */
    public function show(Reminder $reminder): JsonResponse
    {
        // Check if the authenticated user owns the pet associated with this reminder
        if ($reminder->pet->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        return response()->json($reminder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PetReminderUpdateRequest  $request
     * @param  Reminder  $reminder
     * @return JsonResponse
     */
    public function update(PetReminderUpdateRequest $request, Reminder $reminder): JsonResponse
    {
        // Check if the authenticated user owns the pet associated with this reminder
        if ($reminder->pet->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $reminder->update($request->validated());

        return response()->json($reminder);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reminder  $reminder
     * @return JsonResponse
     */
    public function destroy(Reminder $reminder): JsonResponse
    {
        // Check if the authenticated user owns the pet associated with this reminder
        if ($reminder->pet->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $reminder->delete();

        return response()->json(null, 204);
    }
}
