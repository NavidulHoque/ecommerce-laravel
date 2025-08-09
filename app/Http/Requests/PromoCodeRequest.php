<?php

namespace App\Http\Requests;

class PromoCodeRequest extends BasePaginatedRequest
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
        $promoRules = [
            'discount'    => 'integer|min:1|max:99',
            'start_date'  => 'date|after_or_equal:today',
            'expiry_date' => 'date|after:start_date',
            'status'      => 'in:active,expired',
        ];

        if ($this->isMethod('post')) {
            foreach ($promoRules as $field => $rule) {

                if ($field !== 'status') {
                    $promoRules[$field] = 'required|' . $rule;
                }
            }

            $rules = $promoRules;
        }

        else if ($this->isMethod('get')) {

            $paginationRules = parent::rules();

            foreach ($promoRules as $field => $rule) {
                if ($field === 'status') {
                    $promoRules[$field] = 'nullable|' . $rule;
                }
            }

            $promoRules['search'] = 'nullable|string|max:255';

            $rules = array_merge($promoRules, $paginationRules);
        }

        else if ($this->isMethod('patch')) {
            
            foreach ($promoRules as $field => $rule) {
                $promoRules[$field] = 'sometimes|' . $rule;
            }

            $rules = $promoRules;
        }

        return $rules;
    }
}
