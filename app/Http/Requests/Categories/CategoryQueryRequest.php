<?php

namespace App\Http\Requests\Categories;

use App\Http\Requests\BasePaginatedRequest;

class CategoryQueryRequest extends BasePaginatedRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'search' => 'nullable|string'
        ]);
    }
}
