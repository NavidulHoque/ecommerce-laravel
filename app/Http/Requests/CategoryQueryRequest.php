<?php

namespace App\Http\Requests;

class CategoryQueryRequest extends BasePaginatedRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
    }
}
