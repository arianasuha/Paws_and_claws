<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NotificationRegisterRequest;
use App\Http\Requests\NotificationUpdateRequest;

class NotificationController extends Controller
{
    /**
     * Display a listing of the authenticated user's notifications.
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $notifications = $user->notifications()->latest()->paginate(10);
            return response()->json($notifications, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new notification for a specific user.
     * Note: This method is designed to be used by the system or other users,
     * not necessarily for a user to create a notification for themselves directly.
     * The 'user_id' is set automatically from the request data, but you might
     * want to adjust this logic based on your application's needs (e.g., if
     * you want a user to send a notification to another user).
     */
    public function store(NotificationRegisterRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            // Assign the authenticated user's ID as the user who is sending the notification,
            // or modify this logic if the 'user_id' in the request refers to the recipient.
            $validatedData['user_id'] = Auth::id();

            $notification = Notification::create($validatedData);

            return response()->json($notification, 201);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified notification.
     */
    public function show(Notification $notification): JsonResponse
    {
        try {
            // Policy check will handle authorization.
            return response()->json($notification, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified notification in storage (e.g., marking as read).
     */
    public function update(NotificationUpdateRequest $request, Notification $notification): JsonResponse
    {
        try {
            // Policy check will handle authorization.
            $notification->update($request->validated());

            return response()->json([
                'success' => 'Notification updated successfully.',
                'notification' => $notification
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified notification from storage.
     */
    public function destroy(Notification $notification): JsonResponse
    {
        try {
            // Policy check will handle authorization.
            $notification->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }
}
