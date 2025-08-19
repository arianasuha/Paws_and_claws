<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceProviderRegisterRequest;
use App\Http\Requests\ServiceProviderUpdateRequest;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ServiceProviderController extends Controller
{
    /**
     * ServiceProviderController constructor.
     * This middleware ensures that all methods in this controller
     * can only be accessed by authenticated users.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('create');
    }

    /**
     * @OA\Get(
     * path="/api/service-providers",
     * tags={"Service Providers"},
     * summary="Get a list of all service providers",
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/ServiceProvider")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Display a listing of all service providers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $serviceProviders = ServiceProvider::with('user')->get();
            return response()->json($serviceProviders, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve service providers.'
            ], 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/service-providers",
     * tags={"Service Providers"},
     * summary="Create a new service provider (Admin only)",
     * security={{"sanctum":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"user_id","service_type","rate_per_hour"},
     * @OA\Property(property="user_id", type="integer", example="1", description="ID of the user this service provider is associated with"),
     * @OA\Property(property="service_type", type="string", enum={"walker", "groomer", "trainer"}, example="walker"),
     * @OA\Property(property="service_desc", type="string", nullable=true, example="Experienced dog walker available on weekends."),
     * @OA\Property(property="rate_per_hour", type="number", format="float", example="25.00"),
     * @OA\Property(property="rating", type="number", format="float", nullable=true, example="4.5"),
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Service provider created successfully",
     * @OA\JsonContent(ref="#/components/schemas/ServiceProvider")
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden: User does not have admin privileges"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Store a newly created service provider in storage.
     *
     * @param ServiceProviderRegisterRequest $request
     * @return JsonResponse
     */
    public function create(ServiceProviderRegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => $validated['password'],
            ];

            if (isset($validated['address'])) {
                $userData['address'] = $validated['address'];
            }

            $user = User::create($userData);

            $serviceproviderData = array_diff_key($validated, $userData);

            $serrviceproviderData['user_id'] = $user->id;
            $serviceprovider = ServiceProvider::create($serviceproviderData);

            return response()->json([
                "success" => "Service Provider profile created successfully.",
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error creating Service Proider profile: " . $e->getMessage(), ['request_data' => $request->all()]);
            return response()->json(["errors" => "Failed to create Service Provider profile."], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/service-providers/{id}",
     * tags={"Service Providers"},
     * summary="Get a service provider by ID",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the service provider to retrieve"
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/ServiceProvider")
     * ),
     * @OA\Response(
     * response=404,
     * description="Service provider not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Display the specified service provider.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $serviceProvider = ServiceProvider::with('user')->find($id);

            if (!$serviceProvider) {
                return response()->json([
                    'errors' => 'Service provider not found.'
                ], 404);
            }
            return response()->json($serviceProvider, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve service provider.'
            ], 500);
        }
    }

    /**
     * @OA\Patch(
     * path="/api/service-providers/{id}",
     * tags={"Service Providers"},
     * summary="Update an existing service provider (Admin only)",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the service provider to update"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="service_type", type="string", enum={"walker", "groomer", "trainer"}, example="groomer"),
     * @OA\Property(property="service_desc", type="string", nullable=true, example="Certified groomer with 5 years experience."),
     * @OA\Property(property="rate_per_hour", type="number", format="float", example="30.50"),
     * @OA\Property(property="rating", type="number", format="float", nullable=true, example="4.8"),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Service provider updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/ServiceProvider")
     * ),
     * @OA\Response(
     * response=404,
     * description="Service provider not found"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden: User does not have admin privileges"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Update the specified service provider in storage.
     *
     * @param ServiceProviderUpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(ServiceProviderUpdateRequest $request, string $user): JsonResponse
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
                    "errors" => "You are not authorized to update this Service Provider profile."
                ], 403);
            }

            $validated = $request->validated();
            $userFields = ['first_name', 'last_name', 'email', 'username', 'password', 'address'];

            if (isset($validated['password'])) {
                $foundUser->password = $validated['password'];
                unset($validated['password']);
            }

            $userData = array_intersect_key($validated, array_flip($userFields));
            $serviceproviderData = array_diff_key($validated, array_flip($userFields));

            $foundUser->update($userData);

            $serviceprovider = ServiceProvider::where('user_id', $foundUser->id)->first();
            if ($serviceprovider && !empty($serviceproviderData)) {
                $serviceprovider->update($serviceproviderData);
            }


            return response()->json([
                "success" => "Service Provider profile updated successfully.",
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error updating Service Provider profile: " . $e->getMessage(), ['request_data' => $request->all()]);
            return response()->json([
                "error" => "Failed to update Service Provider profile."
            ], 500);
        }
    }



    /**
     * @OA\Delete(
     * path="/api/service-providers/{id}",
     * tags={"Service Providers"},
     * summary="Delete a service provider (Admin only)",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID of the service provider to delete"
     * ),
     * @OA\Response(
     * response=204,
     * description="Service provider deleted successfully"
     * ),
     * @OA\Response(
     * response=404,
     * description="Service provider not found"
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden: User does not have admin privileges"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     *
     * Remove the specified service provider from storage.
     *
     * @param string $id
     * @return JsonResponse
     */

    public function destroy(string $user): JsonResponse
    {
        try {
            $foundUser = User::where('id', $user)
                ->orWhere('slug', $user)
                ->first();

            if (!$foundUser) {
                return response()->json(['error' => 'Service Provider user not found'], 404);
            }

            if (Auth::id() !== $foundUser->id && !Auth::user()->is_admin) {
                return response()->json([
                    'errors' => 'You are not authorized to delete this Service Provider profile.'
                ], 403);
            }

            $serviceprovider = ServiceProvider::where('user_id', $foundUser->id)->first();
            if (!$serviceprovider) {
                return response()->json(["errors" => "Service Provider profile not found."], 404);
            }
            $serviceprovider->user->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error("Error deleting Service Provider profile: " . $e->getMessage());
            return response()->json(["errors" => "Failed to delete Service Provider profile."], 500);
        }
    }
}
