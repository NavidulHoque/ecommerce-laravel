<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function index()
    {
        $cart = Cart::all();

        return response()->json([
            'data' => $cart
        ], 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'user_id' => 'required|integer',
            'items' => 'required|array',
        ]);

        $cart = Cart::create($fields);

        return response()->json([
            'data' => $cart
        ], 201);
    }

    public function show($id)
    {
        $cart = Cart::findOrFail($id);

        return response()->json([
            'data' => $cart
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'user_id' => 'sometimes|required|integer',
            'items' => 'sometimes|required|array',
        ]);

        $cart = Cart::findOrFail($id);
        $cart->update($fields);

        return response()->json([
            'data' => $cart
        ], 200);
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return response()->json([
            'message' => 'Cart deleted successfully'
        ], 200);
    }
}
