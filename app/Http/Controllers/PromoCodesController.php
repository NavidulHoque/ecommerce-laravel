<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoCodeRequest;
use App\Models\PromoCode;

class PromoCodesController extends Controller
{
    public function index(PromoCodeRequest $request)
    {
        $fields = $request->validated();
        $query = PromoCode::query();

        // Search by status
        if (!empty($fields['status'])) {

            $query->where(function ($q) use ($fields) {
                $q->orWhere('status', 'like', '%' . $fields['status'] . '%');
            });
        }

        // Search by creator name
        if (!empty($fields['search'])) {
            $query->whereHas('creator', function ($q) use ($fields) {
                $q->where('name', 'like', '%' . $fields['search'] . '%');
            });
        }

        $per_page = $fields['limit'];

        $promo_codes = $query
            ->with([
                'creator' => function ($query) {
                    $query->select('id', 'name', "email", "profile_image");
                }
            ])
            ->orderBy("created_at", "desc")
            ->paginate($per_page);

        return response()->json([
            'promo_codes' => $promo_codes,
            "message" => "Promo Codes retrieved successfully"
        ], 200);
    }

    public function store(PromoCodeRequest $request)
    {
        $fields = $request->validated();
        $user = $request->user;

        $code = $this->generateCode();
        $fields['code'] = $code;
        $fields['created_by'] = $user->id;

        if (!empty($fields['start_date'])) {
            $fields['start_date'] = $this->formatDateTime($fields['start_date']);
        }

        if (!empty($fields['expiry_date'])) {
            $fields['expiry_date'] = $this->formatDateTime($fields['expiry_date']);
        }

        PromoCode::create($fields);

        return response()->json([
            'message' => 'Promo code created successfully',
            'promo_code' => $fields
        ], 201);
    }

    public function update(PromoCodeRequest $request, $id)
    {
        $fields = $request->validated();
        $user = $request->user;

        $promoCode = $this->findById(PromoCode::class, $id);

        if (!$promoCode) {
            return response()->json(['message' => 'Promo code not found'], 404);
        }

        else if ($user->id !== $promoCode->created_by) {
            return response()->json(['message' => 'You are unauthorized to update this promo code'], 403);
        }

        $promoCode->update($fields);

        return response()->json([
            'message' => 'Promo code updated successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $promoCode = $this->findById(PromoCode::class, $id);

        if (!$promoCode) {
            return response()->json(['message' => 'Promo code not found'], 404);
        }

        $promoCode->delete();

        return response()->json(['message' => 'Promo code deleted successfully'], 200);
    }

    protected function generateCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';

        for ($i = 0; $i < 6; $i++) {
            $result .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $result;
    }
}
