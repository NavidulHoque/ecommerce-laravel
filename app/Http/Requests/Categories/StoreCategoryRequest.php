<?php

namespace App\Http\Requests\Categories;

class StoreCategoryRequest extends BaseCategoryRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
    }
}
