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

class VetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('createVet');
    }

    /**
     * Display a listing of vet profiles.
     * Authorized by VetPolicy's viewAny method via $this->authorize().
     */
    public function index(): JsonResponse
    {
        try {
            $this->authorize('viewAny', Vet::class);

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
     * Store a newly created vet profile in storage.
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
     * Display the specified vet profile.
     * Authorized by 'can:view,vet' middleware.
     * Laravel's route model binding will inject the Vet model.
     */
    public function show($userId): JsonResponse
    {
        try {
            $vet = Vet::where('user_id', $userId)->firstOrFail();
        
            return response()->json($vet->load('user'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["errors" => "Vet profile not found."], 404);
        } catch (AuthorizationException $e) {
            return response()->json(["errors" => "You are not authorized to view this vet profile."], 403);
        } catch (\Exception $e) {
            Log::error("Error fetching vet profile: " . $e->getMessage(), ['vet_id' => $vet->id]);
            return response()->json(["errors" => "Failed to retrieve vet profile."], 500);
        }
    }

    /**
     * Update the specified vet profile in storage.
     * Authorized by 'can:update,vet' middleware.
     */
    public function update(VetUpdateRequest $request, string $userId): JsonResponse
    {
        try {
            $user = User::where('id', $userId)->firstOrFail();
            $vet = Vet::where('user_id', $userId)->firstOrFail();

            $validated = $request->validated();

            $userFields = ['first_name', 'last_name', 'email', 'username', 'password', 'address'];
            $userData = array_intersect_key($validated, array_flip($userFields));
            $vetData = array_diff_key($validated, array_flip($userFields));

            if (isset($userData['password'])) {
                $user->password = $userData['password'];
                unset($userData['password']);
            }
            
            if (!empty($userData)) {
                $user->update($userData);
            }

            if (!empty($vetData)) {
                $vet->update($vetData);
            }

            return response()->json([
                "success" => "Vet profile updated successfully.",
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(["errors" => "Vet profile not found."], 404);
        } catch (AuthorizationException $e) {
            return response()->json(["errors" => "You are not authorized to update this vet profile."], 403);
        } catch (\Exception $e) {
            Log::error("Error updating vet profile: " . $e->getMessage(), ['vet_id' => $vet->id, 'request_data' => $request->all()]);
            return response()->json(["errors" => "Failed to update vet profile."], 500);
        }
    }

    /**
     * Remove the specified vet profile from storage.
     * Authorized by 'can:delete,vet' middleware.
     */
    public function destroy($userId): JsonResponse
    {
        try {
            $vet = Vet::where('user_id', $userId)->firstOrFail();
            $vet->user->delete();

            return response()->json(["success" => "Vet profile deleted successfully."], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(["errors" => "Vet profile not found."], 404);
        } catch (AuthorizationException $e) {
            return response()->json(["errors" => "You are not authorized to delete this vet profile."], 403);
        } catch (\Exception $e) {
            Log::error("Error deleting vet profile: " . $e->getMessage(), ['vet_id' => $vet->id]);
            return response()->json(["errors" => "Failed to delete vet profile."], 500);
        }
    }
}