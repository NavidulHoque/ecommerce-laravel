<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoriesController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::all();

        return response()->json([
            'subcategories' => $subCategories
        ], 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255|unique:sub_categories,name',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subCategories = SubCategory::create($fields);

        return response()->json([
            'message' => 'Subcategory created successfully',
            'subcategory' => $subCategories
        ], 201);
    }

    public function show($id)
    {
        $subCategories = SubCategory::find($id);

        return response()->json([
            'subcategory' => $subCategories
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255|unique:sub_categories,name,' . $id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subCategories = SubCategory::find($id);
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
        $subCategories = SubCategory::find($id);
        if (!$subCategories) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $subCategories->delete();

        return response()->json([
            'message' => 'Subcategory deleted successfully'
        ], 200);
    }
}
