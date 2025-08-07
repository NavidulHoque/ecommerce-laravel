<?php

namespace App\Http\Requests\SubCategories;

use App\Http\Requests\SubCategories\BaseSubCategoryRequest;

class StoreSubCategoryRequest extends BaseSubCategoryRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => 'required|string|max:255|unique:sub_categories,name',
        ]);
    }
}
