<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function store(Request $request)
    {
        $field = $request->validate([
            'product_id' => 'required|integer',
            'user_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::create($field);

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $field,
        ], 201);
    }

    public function show($id)
    {
        $review = Review::findOrFail($id);

        return response()->json($review, 200);
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $field = $request->validate([
            'product_id' => 'required|integer',
            'user_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $review->update($field);

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $field,
        ], 200);

    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ], 200);
    }

    public function index()
    {
        $reviews = Review::orderBy('id','desc')->paginate(10);

        return response()->json([
            'data' => $reviews
        ], 200);
    }
}
