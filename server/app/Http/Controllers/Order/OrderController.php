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
