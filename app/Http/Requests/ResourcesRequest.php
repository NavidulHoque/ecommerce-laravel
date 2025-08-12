<?php

namespace App\Http\Requests;

class ResourcesRequest extends BasePaginatedRequest
{
    public function prepareForValidation()
    {
        // convert status to lowercase for consistency
        if ($this->has('status')) {
            $this->merge([
                'status' => strtolower($this->input('status')),
            ]);
        }
    }

    public function rules(): array
    {
        $resourceRules = [
            'title' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:5',
            'discount_price' => 'numeric|min:0',
            'quantity' => 'integer|min:1',
            'category_id' => 'integer|exists:categories,id',
            'sub_category_id' => 'integer|exists:sub_categories,id',
            "created_by" => 'integer|exists:users,id',
            'status' => 'in:pending,approved,rejected',
            'files.*' => 'required|file|mimes:jpeg,jpg,png,gif,bmp,svg,mp4,mov,avi,mkv,pdf,doc,docx|max:20480'
        ];

        if ($this->isMethod('post')) {

            foreach ($resourceRules as $field => $rule) {

                if ($field === "description") {
                    $resourceRules[$field] = 'nullable|' . $rule;
                }

                else if ($field !== "status" && $field !== "created_by") {
                    $resourceRules[$field] = 'required|' . $rule;
                }
            }
            $rules = $resourceRules;
        }

        else if ($this->isMethod('get')) {

            $paginationRules = parent::rules();

            foreach ($resourceRules as $field => $rule) {

                if ($field === "category_id" || $field === "sub_category_id" || $field === "created_by" || $field === "status") {
                    $resourceRules[$field] = 'nullable|' . $rule;
                }
            }

            $resourceRules["search"] = 'nullable|string|max:255';

            $rules = array_merge($paginationRules, $resourceRules);
        }

        else if ($this->isMethod('patch')) {

            foreach ($resourceRules as $field => $rule) {

                if ($field !== "quantity" && $field !== "category_id" && $field !== "sub_category_id" && $field !== "files.*" && $field !== "created_by") {
                    $resourceRules[$field] = 'sometimes|' . $rule;
                }
            }
            $rules = $resourceRules;
        }

        return $rules;
    }
}
