<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Traits\AuthenticateUser;

class UserController extends Controller
{
    use AuthenticateUser;

    public function index(): JsonResponse
    {
        $checkAuthUser = $this->ensureAuthenticated();

        if ($checkAuthUser) {
            return $checkAuthUser;
        }

        if (!Auth::user()->is_admin) {
            return response()->json([
                "errors" => "You are not authorized to view all users."
            ], 403);
        }

        try {
            $users = User::paginate(10);
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $user): JsonResponse
    {
        $checkAuthUser = $this->ensureAuthenticated();

        if ($checkAuthUser) {
            return $checkAuthUser;
        }

        try {
            $foundUser = User::where('id', $user)
                ->orWhere('slug', $user)
                ->first();

            if (!$foundUser) {
                return response()->json(['error' => 'User not found'], 404);
            }

            if (Auth::id() !== $foundUser->id && !Auth::user()->is_admin) {
                return response()->json([
                    "errors" => "You are not authorized to view this user."
                ], 403);
            }

            return response()->json($foundUser, 200);
        } catch (\Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function createUser(RegisterUserRequest $request): JsonResponse
    {
        if ($request->has('is_admin')) {
            return response()->json([
                "errors" => "You are not authorized to create an admin user."
            ], 403);
        }
        $validated = $request->validated();

        try {
            User::create($validated);

            return response()->json([
                "success" => "User created successfully. Please verify your email to activate your account.",
            ], 201);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function createAdminUser(RegisterUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['is_admin'] = true;

        try {
            User::create($validated);

            return response()->json([
                "success" => "User created successfully. Please verify your email to activate your account.",
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateUserRequest $request, string $user): JsonResponse
    {
        $checkAuthUser = $this->ensureAuthenticated();

        if ($checkAuthUser) {
            return $checkAuthUser;
        }

        if ($request->has('is_admin') || $request->has('is_active') || $request->has('is_vet')) {
            return response()->json([
                "errors" => "You are not authorized to change account status."
            ], 403);
        }

        try {
            $foundUser = User::where('id', $user)
                ->orWhere('slug', $user)
                ->first();

            if (!$foundUser) {
                return response()->json(['error' => 'User not found'], 404);
            }

            if (Auth::id() !== $foundUser->id && !Auth::user()->is_admin) {
                return response()->json([
                    "errors" => "You are not authorized to update this user."
                ], 403);
            }

            $validated = $request->validated();
            \Log::info($validated);
            if (isset($validated['password'])) {
                $foundUser->password = $validated['password'];
                unset($validated['password']);
            }

            $foundUser->update($validated);

            return response()->json([
                "success" => "User updated successfully.",
                'user' => $foundUser->fresh(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, string $user): JsonResponse
    {
        $checkAuthUser = $this->ensureAuthenticated();

        if ($checkAuthUser) {
            return $checkAuthUser;
        }

        try {
            $foundUser = User::where('id', $user)
                ->orWhere('slug', $user)
                ->first();

            if (!$foundUser) {
                return response()->json(['error' => 'User not found'], 404);
            }

            if (Auth::id() !== $foundUser->id) {
                return response()->json([
                    'errors' => 'You are not authorized to delete this user.'
                ], 403);
            }

            $foundUser->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }
}