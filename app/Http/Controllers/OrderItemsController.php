<?php

namespace App\Http\Controllers;

use App\Models\OrderItems;
use Illuminate\Http\Request;

class OrderItemsController extends Controller
{
    public function store(Request $request)
    {
        $fields = $request->validate([
            'order_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $orderItem = OrderItems::create($fields);

        return response()->json([
            'data' => $orderItem,
            'message' => 'Order item created successfully'
        ], 201);
    }

    public function show($id)
    {
        $orderItem = OrderItems::findOrFail($id);

        return response()->json([
            'data' => $orderItem,
            'message' => 'Order item retrieved successfully'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'order_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $orderItem = OrderItems::findOrFail($id);
        $orderItem->update($fields);

        return response()->json([
            'data' => $orderItem,
            'message' => 'Order item updated successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $orderItem = OrderItems::findOrFail($id);
        $orderItem->delete();

        return response()->json([
            'message' => 'Order item deleted successfully'
        ], 200);
    }

    public function index()
    {
        $orders = OrderItems::orderBy('id','desc')->paginate(10);

        return response()->json([
            'data' => $orders
        ], 200);
    }
}
