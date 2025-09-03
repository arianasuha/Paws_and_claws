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
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceProviderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('create');
    }

    /**
     * @OA\Get(
     * path="/api/service-providers",
     * operationId="getServiceProvidersList",
     * tags={"Service Providers"},
     * summary="Get a list of all service providers with search functionality",
     * description="Returns a list of all registered service providers, filtered by a case-insensitive partial match on their user's first name, last name, email, and username.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="search",
     * in="query",
     * description="Case-insensitive search query for user's first name, last name, email, and username.",
     * required=false,
     * @OA\Schema(type="string")
     * ),
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
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/ServiceProvider")
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = ServiceProvider::with('user');

            if ($request->has('search')) {
                $searchTerm = strtolower($request->input('search'));

                $query->whereHas('user', function ($q) use ($searchTerm) {
                    $q->where(DB::raw('lower(first_name)'), 'like', "%{$searchTerm}%")
                    ->orWhere(DB::raw('lower(last_name)'), 'like', "%{$searchTerm}%")
                    ->orWhere(DB::raw('lower(email)'), 'like', "%{$searchTerm}%")
                    ->orWhere(DB::raw('lower(username)'), 'like', "%{$searchTerm}%");
                });
            }

            $serviceProviders = $query->paginate(10);

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
     * operationId="createServiceProvider",
     * tags={"Service Providers"},
     * summary="Register a new service provider",
     * description="Registers a new user and creates a service provider profile. No authentication is required.",
     * @OA\RequestBody(
     * required=true,
     * description="Service Provider registration data",
     * @OA\JsonContent(ref="#/components/schemas/ServiceProviderRegisterRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Service Provider profile created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string", example="Service Provider profile created successfully.")
     * )
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
    public function create(ServiceProviderRegisterRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            // Separate user data from service provider data
            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
            ];

            if (isset($validated['address'])) {
                $userData['address'] = $validated['address'];
            }

            $user = User::create($userData);

            // Separate service provider data from user data
            $serviceproviderData = $request->only(['service_type', 'service_desc', 'rate_per_hour', 'rating']);

            $serviceproviderData['user_id'] = $user->id;
            ServiceProvider::create($serviceproviderData);

            return response()->json([
                "success" => "Service Provider profile created successfully.",
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error creating Service Provider profile: " . $e->getMessage(), ['request_data' => $request->all()]);
            return response()->json(["errors" => "Failed to create Service Provider profile."], 500);
        }
    }


    /**
     * @OA\Get(
     * path="/api/service-providers/{id}",
     * operationId="getServiceProviderById",
     * tags={"Service Providers"},
     * summary="Get a single service provider by ID",
     * description="Returns a single service provider by its ID.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * description="ID of the service provider",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/ServiceProvider")
     * ),
     * @OA\Response(
     * response=404,
     * description="Service provider not found."
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
     */
    public function show(string $id): JsonResponse
    {
        try {
            $serviceProvider = ServiceProvider::with('user')->where('user_id', $id)->first();

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
     * path="/api/service-providers/{user}",
     * operationId="updateServiceProvider",
     * tags={"Service Providers"},
     * summary="Update a service provider profile",
     * description="Updates a service provider's profile. Accessible by the service provider themselves or an admin.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="user",
     * description="ID or slug of the user associated with the service provider",
     * required=true,
     * in="path",
     * @OA\Schema(type="string")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Service Provider update data",
     * @OA\JsonContent(ref="#/components/schemas/ServiceProviderUpdateRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Service Provider profile updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="string", example="Service Provider profile updated successfully.")
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized to update this Service Provider profile."
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
            $serviceProviderFields = ['service_type', 'service_desc', 'rate_per_hour', 'rating'];


            $userData = array_intersect_key($validated, array_flip($userFields));
            $serviceproviderData = array_intersect_key($validated, array_flip($serviceProviderFields));


            if (isset($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }


            if (!empty($userData)) {
                $foundUser->update($userData);
            }


            $serviceProvider = ServiceProvider::where('user_id', $foundUser->id)->first();
            if ($serviceProvider && !empty($serviceproviderData)) {
                $serviceProvider->update($serviceproviderData);
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
     * path="/api/service-providers/{user}",
     * operationId="deleteServiceProvider",
     * tags={"Service Providers"},
     * summary="Delete a service provider profile",
     * description="Deletes a service provider profile. Accessible by the service provider themselves or an admin.",
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="user",
     * description="ID or slug of the user associated with the service provider",
     * required=true,
     * in="path",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=204,
     * description="Service Provider profile deleted successfully"
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized to delete this Service Provider profile."
     * ),
     * @OA\Response(
     * response=404,
     * description="Service Provider profile not found."
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error"
     * )
     * )
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