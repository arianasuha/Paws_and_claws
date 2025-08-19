<?php

namespace App\Http\Controllers\Vet;

use App\Models\Vet;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Vet\VetRegisterRequest;
use App\Http\Requests\Vet\VetUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class VetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('createVet');
    }

    /**
     * @OA\Get(
     * path="/api/vets",
     * operationId="indexVets",
     * tags={"Vets"},
     * summary="Get a list of all vet profiles",
     * description="Returns a paginated list of vet profiles. Requires authentication and administrative privileges.",
     * security={{"sanctum": {}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/VetPaginatedResponse")
     * ),
     * @OA\Response(response=401, description="Unauthenticated"),
     * @OA\Response(response=403, description="Forbidden"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $vets = Vet::with('user')->paginate(10);
            return response()->json($vets, 200);
        } catch (AuthorizationException $e) {
            return response()->json(["errors" => "You are not authorized to view vet listings."], 403);
        } catch (\Exception $e) {
            Log::error("Error fetching vet listings: " . $e->getMessage());
            return response()->json(["errors" => "Failed to retrieve vet listings."], 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/vets",
     * operationId="createVet",
     * tags={"Vets"},
     * summary="Create a new vet profile",
     * description="Registers a new user as a veterinarian and creates their vet profile.",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/VetRegisterRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Vet profile created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string", example="Vet profile created successfully.")
     * )
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function createVet(VetRegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => $validated['password'],
                'is_vet' => true,
            ];

            if (isset($validated['address'])) {
                $userData['address'] = $validated['address'];
            }

            $user = User::create($userData);

            $vetData = array_diff_key($validated, $userData);

            $vetData['user_id'] = $user->id;
            $vet = Vet::create($vetData);

            return response()->json([
                "success" => "Vet profile created successfully.",
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json(["errors" => $e->getMessage()], 403);
        } catch (\Exception $e) {
            Log::error("Error creating vet profile: " . $e->getMessage(), ['request_data' => $request->all()]);
            return response()->json(["errors" => "Failed to create vet profile."], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/vets/{userId}",
     * operationId="showVet",
     * tags={"Vets"},
     * summary="Get a single vet profile by user ID",
     * description="Returns a single vet profile by its user ID. Requires authentication.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="userId",
     * in="path",
     * required=true,
     * description="ID of the user associated with the vet profile to retrieve",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Vet")
     * ),
     * @OA\Response(response=401, description="Unauthenticated"),
     * @OA\Response(response=404, description="Vet profile not found"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function show($userId): JsonResponse
    {
        try {
            $vet = Vet::where('user_id', $userId)->first();

            if (!$vet) {
                return response()->json(['error' => 'Vet user not found'], 404);
            }

            return response()->json($vet->load('user'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["errors" => "Vet profile not found."], 404);
        } catch (AuthorizationException $e) {
            return response()->json(["errors" => "You are not authorized to view this vet profile."], 403);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(["errors" => "Failed to retrieve vet profile."], 500);
        }
    }

    /**
     * @OA\Patch(
     * path="/api/vets/{user}",
     * operationId="updateVet",
     * tags={"Vets"},
     * summary="Update a vet profile by user ID or slug",
     * description="Updates a vet profile. Requires the user to be the owner of the profile or an admin.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="user",
     * in="path",
     * required=true,
     * description="ID or slug of the user associated with the vet profile to update",
     * @OA\Schema(type="string")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/VetUpdateRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Vet profile updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string", example="Vet profile updated successfully.")
     * )
     * ),
     * @OA\Response(response=401, description="Unauthenticated"),
     * @OA\Response(response=403, description="Forbidden"),
     * @OA\Response(response=404, description="Vet profile not found"),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function update(VetUpdateRequest $request, string $user): JsonResponse
    {
        try {
            $foundUser = User::where('id', $user)
                ->orWhere('slug', $user)
                ->first();

            if (!$foundUser) {
                return response()->json(['error' => 'User not found'], 404);
            }

            if (Auth::id() !== $foundUser->id && !Auth::user()->is_admin) {
                return response()->json([
                    "errors" => "You are not authorized to update this vet profile."
                ], 403);
            }

            $validated = $request->validated();
            $userFields = ['first_name', 'last_name', 'email', 'username', 'password', 'address'];

            if (isset($validated['password'])) {
                $foundUser->password = $validated['password'];
                unset($validated['password']);
            }

            $userData = array_intersect_key($validated, array_flip($userFields));
            $vetData = array_diff_key($validated, array_flip($userFields));

            $foundUser->update($userData);

            $vet = Vet::where('user_id', $foundUser->id)->first();
            if ($vet && !empty($vetData)) {
                $vet->update($vetData);
            }


            return response()->json([
                "success" => "Vet profile updated successfully.",
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error updating vet profile: " . $e->getMessage(), ['request_data' => $request->all()]);
            return response()->json([
                "error" => "Failed to update vet profile."
            ], 500);
        }
    }


   /**
     * @OA\Delete(
     * path="/api/vets/{user}",
     * operationId="deleteVet",
     * tags={"Vets"},
     * summary="Delete a vet profile by user ID or slug",
     * description="Deletes a vet profile and the associated user. Requires the user to be the owner of the profile or an admin.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="user",
     * in="path",
     * required=true,
     * description="ID or slug of the user associated with the vet profile to delete",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(response=204, description="Vet profile deleted successfully"),
     * @OA\Response(response=401, description="Unauthenticated"),
     * @OA\Response(response=403, description="Forbidden"),
     * @OA\Response(response=404, description="Vet profile not found"),
     * @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function destroy(string $user): JsonResponse
    {
        try {
            $foundUser = User::where('id', $user)
                ->orWhere('slug', $user)
                ->first();

            if (!$foundUser) {
                return response()->json(['error' => 'Vet user not found'], 404);
            }

            if (Auth::id() !== $foundUser->id && !Auth::user()->is_admin) {
                return response()->json([
                    'errors' => 'You are not authorized to delete this vet profile.'
                ], 403);
            }

            $vet = Vet::where('user_id', $foundUser->id)->first();
            if (!$vet) {
                return response()->json(["errors" => "Vet profile not found."], 404);
            }
            $vet->user->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error("Error deleting vet profile: " . $e->getMessage());
            return response()->json(["errors" => "Failed to delete vet profile."], 500);
        }
    }
}

