<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserReminder;
use App\Http\Requests\User\UserReminderRegisterRequest;
use App\Http\Requests\User\UserReminderUpdateRequest;
use Illuminate\Http\Request;

class UserReminderController extends Controller
{

    public function index()
    {
        // This will return all user_reminder pivot records.
        // A more common use case would be to filter by user_id
        // to show reminders for a specific user.
        $userReminders = UserReminder::all();

        return response()->json([
            'status' => 'success',
            'user_reminders' => $userReminders
        ]);
    }


    public function store(UserReminderRegisterRequest $request)
    {
        $userReminder = UserReminder::create($request->validated());

        return response()->json([
            'status' => 'success',
            'user_reminder' => $userReminder,
        ], 201);
    }

    public function show(UserReminder $userReminder)
    {
        return response()->json([
            'status' => 'success',
            'user_reminder' => $userReminder,
        ]);
    }


    public function update(UserReminderUpdateRequest $request, UserReminder $userReminder)
    {
        $userReminder->update($request->validated());

        return response()->json([
            'status' => 'success',
            'user_reminder' => $userReminder
        ]);
    }


    public function destroy(UserReminder $userReminder)
    {
        $userReminder->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User reminder deleted successfully.'
        ], 204);
    }
}
