<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(CategoryRequest $request)
    {
        $fields = $request->validated();

        $query = Category::query();

        // Filter by user ID
        $query->where('created_by', $request->user->id);

        // Group OR conditions
        if (!empty($fields['search'])) {

            $query->where(function ($q) use ($fields) {
                $q->orWhere('name', 'like', '%' . $fields['search'] . '%');
                $q->orWhere('description', 'like', '%' . $fields['search'] . '%');
            });
        }

        $perPage = $fields['limit'];

        $categories = $query
            ->with([
                'creator' => function ($query) {
                    $query->select('id', 'name', "email", "profile_image");
                }
            ])
            ->orderBy("created_at", "desc")
            ->paginate($perPage);

        return response()->json([
            'categories' => $categories,
            "message" => "Categories retrieved successfully"
        ], 200);
    }

    public function store(CategoryRequest $request)
    {
        $fields = $request->validated();

        $fields["created_by"] = $request->user->id;

        Category::create($fields);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $fields
        ], 201);
    }

    public function update(CategoryRequest $request, $id)
    {
        $user = $request->user;
        $fields = $request->validated();

        $category = $this->findById(Category::class, $id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        else if ($category->created_by !== $user->id) {
            return response()->json(['message' => 'You are unauthorized to update this category'], 403);
        }

        $category->update($fields);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user;
        $category = $this->findById(Category::class, $id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        else if ($category->created_by !== $user->id) {
            return response()->json(['message' => 'You are unauthorized to update this category'], 403);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
