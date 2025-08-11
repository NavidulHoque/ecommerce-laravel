<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CategoryRequest extends BasePaginatedRequest
{
    public function rules(): array
    {
        $categoryRules = [
            'name'        => 'string|max:255|unique:categories,name',
            'description' => 'string',
        ];

        if ($this->isMethod('post')) {

            foreach ($categoryRules as $field => $rule) {
                $categoryRules[$field] = ($field === 'description')
                    ? 'nullable|' . $rule
                    : 'required|' . $rule;
            }

            $rules = $categoryRules;
        }

        else if ($this->isMethod('get')) {

            $categoryRules["search"] = 'nullable|string';

            $rules = array_merge($categoryRules, parent::rules());
        }

        elseif ($this->isMethod('patch') || $this->isMethod('put')) {

            foreach ($categoryRules as $field => $rule) {

                if ($field === 'name') {
                    $categoryRules[$field] = [
                        'sometimes',
                        'string',
                        'max:255',
                        Rule::unique('categories', 'name')->ignore($this->route('id'))
                    ];
                }

                else {
                    $categoryRules[$field] = 'sometimes|' . $rule;
                }
            }

            $rules = $categoryRules;
        }

        return $rules;
    }
}
