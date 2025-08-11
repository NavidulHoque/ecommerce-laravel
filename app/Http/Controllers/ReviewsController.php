<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;

class ReviewsController extends Controller
{
    public function store(ReviewRequest $request)
    {
        $field = $request->validated();
        $field['user_id'] = $request->user->id;

        Review::create($field);

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $field,
        ], 201);
    }

    public function index(ReviewRequest $request, $resource_id)
    {
        $fields = $request->validated();
        $limit = $fields['limit'];

        $reviews = Review::where('resource_id', $resource_id)
                ->orderBy('created_at', 'desc')
                ->paginate($limit);

        return response()->json([
            'data' => $reviews,
            "message" => 'Reviews retrieved successfully'
        ], 200);
    }

    public function update(ReviewRequest $request, $id)
    {
        $user = $request->user;
        $review = $this->findById(Review::class, $id);
        $field = $request->validated();

        if (!$review) {
            return response()->json([
                'message' => 'Review not found',
            ], 404);
        }

        else if ($user->id != $review->user_id) {
            return response()->json([
                'message' => 'You are not authorized to update this review',
            ], 403);
        }

        $review->update($field);

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $field,
        ], 200);
    }

    public function destroy($id)
    {
        $review = $this->findById(Review::class, $id);

        if (!$review) {
            return response()->json([
                'message' => 'Review not found',
            ], 404);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ], 200);
    }
}
