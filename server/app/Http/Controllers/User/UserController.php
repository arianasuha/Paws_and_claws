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
use App\Models\Cart;

class UserController extends Controller
{
    use AuthenticateUser;

    /**
     * @OA\Get(
     * path="/api/users",
     * operationId="indexUsers",
     * tags={"Users"},
     * summary="Get all users (Admin only)",
     * description="Returns a paginated list of all users. Requires admin privileges.",
     * security={{"sanctum": {}}},
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
     * @OA\JsonContent(ref="#/components/schemas/UserPaginatedResponse")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
     */
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

    /**
     * @OA\Get(
     * path="/api/users/{user}",
     * operationId="showUser",
     * tags={"Users"},
     * summary="Get a single user by ID or slug",
     * description="Returns a single user by their ID or slug. Requires authentication. A user can view their own profile, but only an admin can view others.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="user",
     * in="path",
     * required=true,
     * description="ID or slug of the user",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/User")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
     */
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

    /**
     * @OA\Post(
     * path="/api/users",
     * operationId="createUser",
     * tags={"Users"},
     * summary="Register a new user",
     * description="Creates a new user account.",
     * @OA\RequestBody(
     * required=true,
     * description="User registration payload",
     * @OA\JsonContent(ref="#/components/schemas/RegisterUserRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="User created successfully"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * )
     * )
     */
    public function createUser(RegisterUserRequest $request): JsonResponse
    {
        if ($request->has('is_admin')) {
            return response()->json([
                "errors" => "You are not authorized to create an admin user."
            ], 403);
        }
        $validated = $request->validated();

        try {
            $user = User::create($validated);
            Cart::create(['user_id' => $user->id]);

            return response()->json([
                "success" => "User created successfully.",
            ], 201);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                "errors" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Patch(
     * path="/api/users/{user}",
     * operationId="updateUser",
     * tags={"Users"},
     * summary="Update a user by ID or slug",
     * description="Updates an existing user. Requires authentication. A user can update their own profile, but only an admin can update others. Restricted fields like `is_admin` cannot be updated.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="user",
     * in="path",
     * required=true,
     * description="ID or slug of the user to update",
     * @OA\Schema(type="string")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="User update payload",
     * @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="User updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string"),
     * @OA\Property(property="user", ref="#/components/schemas/User")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found"
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
                "success" => "User updated successfully."
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/users/{user}",
     * operationId="deleteUser",
     * tags={"Users"},
     * summary="Delete a user",
     * description="Deletes an authenticated user's account. Requires user to be authenticated and deleting their own account.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="user",
     * in="path",
     * required=true,
     * description="ID or slug of the user to delete",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=204,
     * description="User deleted successfully"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden"
     * ),
     * @OA\Response(
     * response=404,
     * description="User not found"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
     */
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

            if (Auth::id() !== $foundUser->id && !Auth::user()->is_admin) {
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