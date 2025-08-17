<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CartRequest;
use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * @OA\Get(
     * path="/carts",
     * operationId="getCarts",
     * summary="Get the authenticated user's cart",
     * description="Retrieves all items in the authenticated user's shopping cart.",
     * tags={"Carts"},
     * security={{"sanctum": {}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(ref="#/components/schemas/Cart")
     * )
     * )
     * )
     * )
     *
     * Get the authenticated user's cart items.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieve all cart items for the authenticated user and load the related petProduct.
        $cartItems = Auth::user()->carts()->with('petProduct')->get();

        return response()->json([
            'data' => $cartItems
        ]);
    }

    /**
     * @OA\Post(
     * path="/carts",
     * operationId="createCartItem",
     * summary="Add a product to the cart",
     * description="Adds a new product to the authenticated user's cart. If the product already exists, it updates the quantity.",
     * tags={"Carts"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/CartStoreRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Product added to cart successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Product added to cart successfully."),
     * @OA\Property(property="data", ref="#/components/schemas/Cart")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     *
     * Add a product to the cart or update the quantity of an existing item.
     *
     * @param CartRequest $request
     * @return JsonResponse
     */
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

    /**
     * @OA\Patch(
     * path="/carts/{cart}",
     * operationId="updateCartItem",
     * summary="Update a specific cart item",
     * description="Updates the quantity of a specific cart item owned by the authenticated user.",
     * tags={"Carts"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="cart",
     * in="path",
     * required=true,
     * description="ID of the cart item to update",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/CartUpdateRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Cart item updated successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Cart item updated successfully."),
     * @OA\Property(property="data", ref="#/components/schemas/Cart")
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized action"
     * ),
     * @OA\Response(
     * response=404,
     * description="Cart item not found"
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * )
     * )
     *
     * Update the quantity of a specific cart item.
     *
     * @param CartRequest $request
     * @param Cart $cart
     * @return JsonResponse
     */
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

    /**
     * @OA\Delete(
     * path="/carts/{cart}",
     * operationId="deleteCartItem",
     * summary="Remove a cart item",
     * description="Removes a specific product from the authenticated user's cart.",
     * tags={"Carts"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="cart",
     * in="path",
     * required=true,
     * description="ID of the cart item to remove",
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Product removed from cart successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Product removed from cart successfully.")
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Unauthorized action"
     * ),
     * @OA\Response(
     * response=404,
     * description="Cart item not found"
     * )
     * )
     *
     * Remove a cart item.
     *
     * @param Cart $cart
     * @return JsonResponse
     */
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

    /**
     * @OA\Delete(
     * path="/carts/clear",
     * operationId="clearCart",
     * summary="Clear all items from the cart",
     * description="Deletes all products from the authenticated user's cart.",
     * tags={"Carts"},
     * security={{"sanctum": {}}},
     * @OA\Response(
     * response=200,
     * description="All items removed from cart successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="All items removed from cart.")
     * )
     * )
     * )
     *
     * Clear all items from the user's cart.
     *
     * @return JsonResponse
     */
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
