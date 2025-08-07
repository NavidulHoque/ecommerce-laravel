<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategories\QuerySubCategoryRequest;
use App\Http\Requests\SubCategories\StoreSubCategoryRequest;
use App\Http\Requests\SubCategories\UpdateSubCategoryRequest;
use App\Models\SubCategory;

class SubCategoriesController extends Controller
{
    public function index(QuerySubCategoryRequest $request, $categoryId)
    {
        $fields = $request->validated();
        $query = SubCategory::query();

        // Filter by category ID
        $query->where('category_id', $categoryId);

        // Group OR conditions
        if (!empty($fields['search'])) {

            $query->where(function ($q) use ($fields) {
                $q->orWhere('name', 'like', '%' . $fields['search'] . '%');
                $q->orWhere('description', 'like', '%' . $fields['search'] . '%');
            });
        }

        $perPage = $fields['limit'];

        $subCategories = $query
            ->select('id', 'name', 'description', "created_at", "updated_at")
            ->orderBy("created_at", "desc")
            ->paginate($perPage);

        return response()->json([
            'sub_categories' => $subCategories,
            "message" => "Sub Categories retrieved successfully"
        ], 200);
    }

    public function store(StoreSubCategoryRequest $request)
    {
        $fields = $request->validated();

        $subCategories = SubCategory::create($fields);

        return response()->json([
            'message' => 'Subcategory created successfully',
            'subcategory' => $subCategories
        ], 201);
    }

    public function update(UpdateSubCategoryRequest $request, $id)
    {
        $fields = $request->validated();

        $subCategories = $this->findById(SubCategory::class, $id);
        if (!$subCategories) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $subCategories->update($fields);

        return response()->json([
            'message' => 'Subcategory updated successfully',
            'subcategory' => $subCategories
        ], 200);
    }

    public function destroy($id)
    {
        $subCategories = $this->findById(SubCategory::class, $id);
        if (!$subCategories) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $subCategories->delete();

        return response()->json([
            'message' => 'Subcategory deleted successfully'
        ], 200);
    }
}
