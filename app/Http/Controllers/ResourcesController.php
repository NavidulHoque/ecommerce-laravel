<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourcesRequest;
use App\Models\Resource;
use App\Models\ResourceFiles;

class ResourcesController extends Controller
{
    public function index(ResourcesRequest $request)
    {
        $user = $request->user;
        $fields = $request->validated();

        $query = Resource::query();

        if (in_array($user->role, ['seller', 'admin'])) {

            $filters = ['category_id', 'sub_category_id', 'created_by', 'status'];

            // Apply AND filters
            foreach ($filters as $filter) {
                if (!empty($fields[$filter])) {
                    $query->where($filter, $fields[$filter]);
                }
            }

            // Search filter with OR conditions
            if (!empty($fields['search'])) {

                $search = '%' . $fields['search'] . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', $search)
                        ->orWhere('description', 'like', $search)
                        ->orWhereHas('creator', fn($q) => $q->where('name', 'like', $search));
                });
            }

        } else if ($user->role === 'buyer') {

            // Search filter for buyer
            if (!empty($fields['search'])) {

                $search = '%' . $fields['search'] . '%';

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', $search)
                        ->orWhere('description', 'like', $search)
                        ->orWhereHas('category', fn($q) => $q->where('name', 'like', $search))
                        ->orWhereHas('sub_category', fn($q) => $q->where('name', 'like', $search));
                });
            }

            // Buyers only see approved resources
            $query->where('status', 'approved');
        }

        $limit = $fields['limit'];

        $resources = $query
            ->with([
                'creator:id,name,email,profile_image'
            ])
            ->orderByDesc('created_at')
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
