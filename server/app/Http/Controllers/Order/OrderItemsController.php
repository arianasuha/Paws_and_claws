<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderItemRegisterRequest;
use App\Http\Requests\Order\OrderItemUpdateRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderItemsController extends Controller
{

    public function index(string $order): JsonResponse
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
                    'errors' => 'You do not have permission to view these order items.'
                ], 403);
            }

            // Eager load the petProduct relationship to avoid N+1 query problem
            $orderItems = $order->items()->with('petProduct')->get();

            // Map the collection to include the product name directly
            $itemsWithProductNames = $orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'order_id' => $item->order_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'product_name' => $item->petProduct->name, // Assuming 'name' is the column with the product name
                ];
            });

            return response()->json($itemsWithProductNames, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to retrieve order items.'
            ], 500);
        }
    }


    public function store(OrderItemRegisterRequest $request, string $order): JsonResponse
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
                    'errors' => 'You do not have permission to add items to this order.'
                ], 403);
            }

            $orderItem = $order->items()->create($request->validated());

            return response()->json($orderItem, 201);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to create order item.'
            ], 500);
        }
    }


    public function update(OrderItemUpdateRequest $request, string $order): JsonResponse
    {
        try {
            $order = Order::find($order);
            if (!$order) {
                return response()->json([
                    'errors' => 'Order not found.'
                ], 404);
            }

            $item = $order->items()->find($request->id);
            if (!$item) {
                return response()->json([
                    'errors' => 'Order item not found.'
                ], 404);
            }
            if ($order->user_id !== auth()->id() && !Auth::user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to update this order item.'
                ], 403);
            }
            if ($item->order_id !== $order->id) {
                return response()->json([
                    'errors' => 'Order item does not belong to the specified order.'
                ], 404);
            }

            $item->update($request->validated());

            return response()->json($item, 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to update order item.'
            ], 500);
        }
    }


    public function destroy(Order $order, OrderItem $item): JsonResponse
    {
        try {
            if ($order->user_id !== auth()->id() && !Auth::user()->is_admin) {
                return response()->json([
                    'errors' => 'You do not have permission to delete this order item.'
                ], 403);
            }
            if ($item->order_id !== $order->id) {
                return response()->json([
                    'errors' => 'Order item does not belong to the specified order.'
                ], 404);
            }

            $item->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Failed to delete order item.'
            ], 500);
        }
    }
}
