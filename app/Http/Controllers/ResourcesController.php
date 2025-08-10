<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourcesRequest;
use App\Models\Resource;
use App\Models\ResourceFiles;
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

    public function store(ResourcesRequest $request)
    {
        $fields = $request->validated();
        $fields['created_by'] = $request->user->id;
        $fields['product_id'] = $this->generateCode();

        $resource = Resource::create($fields);
        $files = $request->file('files');

        if ($files && is_array($files)) {

            foreach ($files as $file) {
                // Get original filename without extension
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                // Sanitize original name to avoid special character issues
                $originalName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);

                // Generate unique filename with original name
                $filename = time() . '_' . uniqid() . '_' . $originalName . '.' . $file->getClientOriginalExtension();

                // Store file locally in 'storage/app/private/public/uploads'
                $file->storeAs('public/uploads', $filename);

                // Save file info in DB
                ResourceFiles::create([
                    'resource_id' => $resource->id,
                    'file_url' => 'storage/uploads/' . $filename, // relative URL
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return response()->json([
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

    public function update(ResourcesRequest $request, $id)
    {
        $fields = $request->validated();

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
