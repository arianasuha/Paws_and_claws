<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * Login user and return token with expiry.
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="suha@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Laravel@123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|abcdefghijklmnopqrstuvwxyz"),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_at", type="string", example="2023-12-31 23:59:59"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="string", example="Credentials are incorrect."),
     *         ),
     *     ),
     * )
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();

        // Check password
        if (!$user || !Hash::check($validated['password'], $user->password) || !$user->is_active) {
            return response()->json([
                'errors' => 'Credentials are incorrect.'
            ], 422);
        }

        // Create token with 24-hour expiry
        $token = $user->createToken('auth_token', ['*'], now()->addHours(24))->plainTextToken;

        $role = 'user';

        if ($user->is_admin) {
            $role = 'admin';
        } elseif ($user->is_vet) {
            $role = 'vet';
        } elseif ($user->serviceProvider()->exists()) {
            $role = 'provider';
        }

        return response()->json([
            'token' => $token,
            'user_id' => $user->id,
            'user_role' => $role,
            'token_type' => 'Bearer',
            'token_expiry' => now()->addHours(24)->toDateTimeString(),
        ]);
    }

    /**
     * Logout user (revoke token).
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout a user",
     *     tags={"Authentication"},
     *     security={ {"sanctum": {} } },
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Successfully logged out."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="string", example="You are not authenticated"),
     *         ),
     *     ),
     * )
     */
    public function logout(): JsonResponse
    {
        /** @var PersonalAccessToken $accessToken */
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'success' => 'Successfully logged out.',
        ]);
    }
}