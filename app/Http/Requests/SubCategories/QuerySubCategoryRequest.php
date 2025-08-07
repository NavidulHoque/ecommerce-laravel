<?php

namespace App\Http\Requests\SubCategories;

use App\Http\Requests\BasePaginatedRequest;

class QuerySubCategoryRequest extends BasePaginatedRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'search' => 'nullable|string'
        ]);
    }
}
