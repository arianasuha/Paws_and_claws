<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRegisterRequest;
use App\Http\Requests\Order\OrderUpdateRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/orders",
     * summary="Get a paginated list of orders",
     * tags={"Orders"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="page",
     * in="query",
     * description="Page number",
     * required=false,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * description="Number of items per page",
     * required=false,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/OrderPaginatedResponse")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            $userId = auth()->id();

            if (!Auth::user()->is_admin) {
                $orders = Order::where('user_id', $userId)->paginate($perPage);
            } else {
                $orders = Order::paginate($perPage);
            }

            return response()->json($orders, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve orders.'
            ], 500);
        }
    }


    /**
     * @OA\Post(
     * path="/api/orders",
     * summary="Create a new order",
     * tags={"Orders"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/OrderRegisterRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Order created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Order")
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     */
    public function createOrder(OrderRegisterRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->id();
            $order = Order::create($validatedData);

            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to create order.'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     * path="/api/orders/{order}",
     * summary="Get a single order by ID",
     * tags={"Orders"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="order",
     * in="path",
     * description="ID of the order to retrieve",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Order")
     * ),
     * @OA\Response(
     * response=404,
     * description="Order not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     */
    public function show(string $order): JsonResponse
    {
        try {
            $order = Order::find($order);
            if (!$order) {
                return response()->json([
                    'errors' => 'Order not found.'
                ], 404);
            }
            if ($order->user_id !== auth()->id() && !Auth::user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to view this order.'
                ], 403);
            }

            return response()->json($order, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve order.'
            ], 500);
        }
    }

    /**
     * @OA\Patch(
     * path="/api/orders/{order}",
     * summary="Update an existing order",
     * tags={"Orders"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="order",
     * in="path",
     * description="ID of the order to update",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/OrderUpdateRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Order updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Order")
     * ),
     * @OA\Response(
     * response=404,
     * description="Order not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     * )
     */
    public function update(OrderUpdateRequest $request, string $order): JsonResponse
    {
        try {
            $order = Order::find($order);
            if (!$order) {
                return response()->json([
                    'errors' => 'Order not found.'
                ], 404);
            }

            if ($order->user_id !== auth()->id()) {
                return response()->json([
                    'errors' => 'You do not have permission to update this order.'
                ], 403);
            }

            $validatedData = $request->validated();
            $order->update($validatedData);

            return response()->json($order, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to update order.'
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/orders/{order}",
     * summary="Delete an order",
     * tags={"Orders"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="order",
     * in="path",
     * description="ID of the order to delete",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Order deleted successfully"
     * ),
     * @OA\Response(
     * response=404,
     * description="Order not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     */
    public function destroy(string $order): JsonResponse
    {
        try {
            $order = Order::find($order);
            if (!$order) {
                return response()->json([
                    'errors' => 'Order not found.'
                ], 404);
            }

            if ($order->user_id !== auth()->id()) {
                return response()->json([
                    'errors' => 'You do not have permission to delete this order.'
                ], 403);
            }

            $order->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to delete order.'
            ], 500);
        }
    }
}
