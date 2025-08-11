<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\CartItems;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function store(CartRequest $request)
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

    public function show(CartRequest $request)
    {
        $user = $request->user;
        $fields = $request->validated();
        $cart = Cart::where('user_id', $user->id)->first();

        $cartItems = CartItems::where('cart_id', $cart->id)
            ->with([
                'resource' => function ($query) {
                    $query->select('id', 'title', "price", "discount_price");
                }
            ])
            ->orderBy("created_at", "desc")
            ->paginate($fields["limit"]);

        return response()->json([
            'data' => $cartItems,
            "message" => 'Cart retrieved successfully'
        ], 200);
    }

    public function update(CartRequest $request, $id)
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
