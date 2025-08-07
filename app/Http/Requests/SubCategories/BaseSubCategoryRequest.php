<?php

namespace App\Http\Requests\SubCategories;

use Illuminate\Foundation\Http\FormRequest;

class BaseSubCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id'
        ];
    }
}
