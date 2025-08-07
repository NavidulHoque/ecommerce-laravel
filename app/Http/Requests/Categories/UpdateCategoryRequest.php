<?php

namespace App\Http\Requests\Categories;

use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends BaseCategoryRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->route('id')),
            ]
        ]);
    }
}
