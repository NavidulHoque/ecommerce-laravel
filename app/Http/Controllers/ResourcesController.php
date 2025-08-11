<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourcesRequest;
use App\Models\Resource;
use App\Models\ResourceFiles;

class ResourcesController extends Controller
{
    public function index(ResourcesRequest $request)
    {
        $fields = $request->validated();

        $query = Resource::query();

        // Apply filters with AND logic
        if (!empty($fields['category_id'])) {
            $query->where('category_id', $fields['category_id']);
        }

        if (!empty($fields['sub_category_id'])) {
            $query->where('sub_category_id', $fields['sub_category_id']);
        }

        if (!empty($fields['created_by'])) {
            $query->where('created_by', $fields['created_by']);
        }

        if (!empty($fields['status'])) {
            $query->where('status', $fields['status']);
        }

        // Group OR conditions
        if (!empty($fields['search'])) {

            $search = '%' . $fields['search'] . '%';

            $query->where(function ($q) use ($search) {
                $q->orWhere('title', 'like', $search);
                $q->orWhere('description', 'like', $search);
                $q->orWhereHas('creator', function ($q) use ($search) {
                    $q->where('name', 'like', $search);
                });
            });
        }

        $limit = $fields['limit'];

        $resources = $query
            ->with([
                'creator' => function ($query) {
                    $query->select('id', 'name', "email", "profile_image");
                }
            ])
            ->orderBy("created_at", "desc")
            ->paginate($limit);

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
                    'file_url' => 'storage/uploads/' . $filename,
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Resource created successfully'
        ]);
    }

    public function update(ResourcesRequest $request, $id)
    {
        $fields = $request->validated();

        $resource = $this->findById(Resource::class, $id);

        if (!$resource) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        $resource->update($fields);

        return response()->json([
            'data' => $resource,
            'message' => 'Resource updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $resource = $this->findById(Resource::class, $id);

        if (!$resource) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        $resource->delete();

        return response()->json([
            'message' => 'Resource deleted successfully'
        ]);
    }
}
