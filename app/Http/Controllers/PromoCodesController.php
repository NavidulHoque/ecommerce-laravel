<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodesController extends Controller
{
    public function index()
    {
        $codes = PromoCode::all();
        return response()->json($codes, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'code' => 'required|string|max:255',
            'discount' => 'required|numeric',
            'expires_at' => 'nullable|date',
        ]);

        PromoCode::create($fields);

        return response()->json([
            'message' => 'Promo code created successfully',
            'promo_code' => $fields
        ], 201);
    }

    public function show($id)
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return response()->json(['message' => 'Promo code not found'], 404);
        }

        return response()->json($promoCode, 200);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'code' => 'required|string|max:255',
            'discount' => 'required|numeric',
            'expires_at' => 'nullable|date',
        ]);

        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return response()->json(['message' => 'Promo code not found'], 404);
        }

        $promoCode->update($fields);

        return response()->json([
            'message' => 'Promo code updated successfully',
            'promo_code' => $fields
        ], 200);
    }

    public function destroy($id)
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return response()->json(['message' => 'Promo code not found'], 404);
        }

        $promoCode->delete();

        return response()->json(['message' => 'Promo code deleted successfully'], 200);
    }
}
