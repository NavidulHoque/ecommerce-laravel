<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy("id","desc")->paginate(10);

        return response()->json([
            'data' => $orders
        ], 200);
    }

    public function store(Request $request)
    {

        $field = $request->validate([
            'customer_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric',
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $order = Order::create($field);

        return response()->json([
            'data' => $order
        ], 200);
    }

    public function show($id)
    {
        $order = Order::find($id);

        return response()->json([
            'data' => $order
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $field = $request->validate([
            'customer_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric',
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $order = Order::find($id);
        $order->update($field);

        return response()->json([
            'data' => $order
        ], 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
