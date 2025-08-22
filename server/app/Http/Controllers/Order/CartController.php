<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CartRequest;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index(): JsonResponse
    {
        // Retrieve all cart items for the authenticated user and load the related petProduct.
        $cartItems = Auth::user()->carts()->with('petProduct')->get();

        return response()->json([
            'data' => $cartItems
        ]);
    }


    public function store(CartRequest $request): JsonResponse
    {
        $user = Auth::user();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Check if the product already exists in the user's cart
        $cartItem = $user->carts()->where('product_id', $productId)->first();

        if ($cartItem) {
            // If the item exists, update its quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // If not, create a new cart item
            $cartItem = $user->carts()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully.',
            'data' => $cartItem->load('petProduct')
        ], 201);
    }

        public function update(CartRequest $request, Cart $cart): JsonResponse
    {
        // Check if the authenticated user owns this cart item
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Update the quantity
        $cart->quantity = $request->input('quantity');
        $cart->save();

        return response()->json([
            'message' => 'Cart item updated successfully.',
            'data' => $cart->load('petProduct')
        ]);
    }


    public function destroy(Cart $cart): JsonResponse
    {
        // Check if the authenticated user owns this cart item
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'message' => 'Product removed from cart successfully.'
        ]);
    }


    public function clear(): JsonResponse
    {
        $user = Auth::user();

        // Delete all cart items belonging to the authenticated user
        $user->carts()->delete();

        return response()->json([
            'message' => 'All items removed from cart.'
        ]);
    }
}
