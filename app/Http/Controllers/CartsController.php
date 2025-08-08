<?php

namespace App\Http\Controllers;

use App\Http\Requests\Carts\StoreCartRequest;
use App\Http\Requests\Carts\UpdateCartRequest;
use App\Models\Cart;
use App\Models\CartItems;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function store(StoreCartRequest $request)
    {
        $user = $request->user;
        $cartItemFields = $request->validated();

        // Find or create cart
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItemFields["cart_id"] = $cart->id;

        // Then create the cart item
        CartItems::Create($cartItemFields);

        return response()->json([
            'message' => 'Cart created successfully'
        ], 201);
    }

    public function show(Request $request)
    {
        $user = $request->user;
        $cart = Cart::with('items.resource')->where('user_id', $user->id)->first();

        return response()->json([
            'data' => $cart,
            "message" => 'Cart retrieved successfully'
        ], 200);
    }

    public function update(UpdateCartRequest $request, $id)
    {
        $fields = $request->validated();

        $item = $this->findById(CartItems::class, $id);

        if (!$item) {
            return response()->json([
                'message' => 'Cart item not found'
            ], 404);
        }

        $item->update($fields);

        return response()->json([
            "message" => "Cart item updated successfully"
        ], 200);
    }

    public function destroy($cart_item_id, $cart_id)
    {
        if ($cart_id) {
            $cart = $this->findById(Cart::class, $cart_id);

            if (!$cart) {
                return response()->json([
                    'message' => 'Cart not found'
                ], 404);
            }

            $cart->delete();
        }

        $cart_item = $this->findById(CartItems::class, $cart_item_id);

        if (!$cart_item) {
            return response()->json([
                'message' => 'Cart item not found'
            ], 404);
        }

        $cart_item->delete();

        return response()->json([
            'message' => 'Cart deleted successfully'
        ], 200);
    }
}
