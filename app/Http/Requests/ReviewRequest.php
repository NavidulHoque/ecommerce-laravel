<?php

namespace App\Http\Requests;

class ReviewRequest extends BasePaginatedRequest
{
    public function rules(): array
    {
        $reviewRules = [
            'resource_id' => 'integer|exists:resources,id',
            'rating'      => 'integer|min:1|max:5',
            'comment'     => 'string',
        ];

        if ($this->isMethod('post')) {

            foreach ($reviewRules as $field => $rule) {
                $reviewRules[$field] = 'required|' . $rule;
            }

            $rules = $reviewRules;
        }

        elseif ($this->isMethod('get')) {

            $rules = parent::rules();
        }

        elseif ($this->isMethod('patch')) {

            foreach ($reviewRules as $field => $rule) {

                if($field !== 'resource_id') {
                    $reviewRules[$field] = 'sometimes|' . $rule;
                }
            }

            $rules = $reviewRules;
        }

        return $rules;
    }
}
