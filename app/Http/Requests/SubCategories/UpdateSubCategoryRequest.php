<?php

namespace App\Http\Requests\SubCategories;

use App\Http\Requests\SubCategories\BaseSubCategoryRequest;
use Illuminate\Validation\Rule;

class UpdateSubCategoryRequest extends BaseSubCategoryRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories', 'name')->ignore($this->route('id')),
            ]
        ]);
    }
}
