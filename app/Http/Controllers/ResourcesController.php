<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    public function index()
    {
        $resources = Resource::all();

        return response()->json([
            'data' => $resources,
            'message' => 'List of resources'
        ]);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);

        $resource = Resource::create($fields);

        return response()->json([
            'data' => $resource,
            'message' => 'Resource created successfully'
        ]);
    }

    public function show($id)
    {
        $resource = Resource::findOrFail($id);

        return response()->json([
            'data' => $resource,
            'message' => 'Resource details'
        ]);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'sub_category_id' => 'sometimes|required|exists:sub_categories,id',
        ]);

        $resource = Resource::findOrFail($id);
        $resource->update($fields);

        return response()->json([
            'data' => $resource,
            'message' => 'Resource updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->delete();

        return response()->json([
            'message' => 'Resource deleted successfully'
        ]);
    }
}
