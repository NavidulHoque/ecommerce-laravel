<?php

namespace App\Http\Requests;

class CartRequest extends BasePaginatedRequest
{
    public function rules(): array
    {
        $cartRules = [
            'resource_id' => 'exists:resources,id',
            'quantity' => 'integer|min:1'
        ];

        if ($this->isMethod('post')) {

            foreach ($cartRules as $field => $rule) {
                $cartRules[$field] = 'required|' . $rule;
            }

            $rules = $cartRules;
        }

        elseif ($this->isMethod('get')) {

            $rules = parent::rules();
        }

        elseif ($this->isMethod('patch')) {
            $cartRules["quantity"] = 'sometimes' . $cartRules["quantity"];

            $rules = $cartRules;
        }

        return $rules;
    }
}
