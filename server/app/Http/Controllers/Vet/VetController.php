<?php

namespace App\Http\Controllers\Vet;

use App\Models\Vet;
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
        $this->middleware('auth:sanctum');

        $this->middleware('can:view,vet')->only('show');
        $this->middleware('can:update,vet')->only('update');
        $this->middleware('can:delete,vet')->only('destroy');

    }

    /**
     * Display a listing of vet profiles.
     * Authorized by VetPolicy's viewAny method via $this->authorize().
     */
    public function index(): JsonResponse
    {
        try {
            $this->authorize('viewAny', Vet::class);

            $vets = Vet::with('user')->get();
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
     * Authorized by VetPolicy's create method via $this->authorize().
     */
    public function createVet(VetRegisterRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Vet::class);

            $validated = $request->validated();
            $vet = Vet::create($validated);

            return response()->json([
                "success" => "Vet profile created successfully.",
                "vet" => $vet->load('user'),
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
    public function show(Vet $vet): JsonResponse
    {
        try {
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
    public function update(VetUpdateRequest $request, Vet $vet): JsonResponse
    {
        try {

            $validated = $request->validated();
            unset($validated['user_id']);

            $vet->update($validated);

            return response()->json([
                "success" => "Vet profile updated successfully.",
                "vet" => $vet->fresh()->load('user'),
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
    public function destroy(Vet $vet): JsonResponse
    {
        try {
            $vet->delete();

            return response()->json(["success" => "Vet profile deleted successfully."], 200);
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