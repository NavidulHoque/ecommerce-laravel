<?php

namespace App\Http\Controllers;

use App\Models\CartItems;
use Illuminate\Http\Request;

class CartItemsController extends Controller
{
    public function index()
    {
        $items = CartItems::all();

        return response()->json(
            $items,
            200,
        );
    }

    public function store(Request $request)
    {

        $fields = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItems::create($fields);

        return response()->json(
            $item,
            201,
        );
    }

    public function show($id)
    {
        $item = CartItems::find($id);

        return response()->json(
            $item,
            200,
        );
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItems::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->update($fields);

        return response()->json(
            $item,
            200,
        );
    }

    public function destroy($id)
    {
        $item = CartItems::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->delete();

        return response()->json(
            ['message' => 'Item deleted successfully'],
            200,
        );
    }
}
