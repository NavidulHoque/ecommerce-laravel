<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'discount'    => 'integer|min:1|max:99',
            'start_date'  => 'date|after_or_equal:today',
            'expiry_date' => 'date|after:start_date',
            'status'      => 'in:Active,Expired',
        ];

        // For POST (store) → make them required
        if ($this->isMethod('post')) {
            foreach ($rules as $field => $rule) {
                $rules[$field] = 'required|' . $rule;
            }
        }

        // For POST (store) → make them required
        if ($this->isMethod('get')) {
            foreach ($rules as $field => $rule) {
                $rules[$field] = 'nullable|' . $rule;
            }
        }

        // For PATCH (update) → keep them as optional
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as $field => $rule) {
                $rules[$field] = 'sometimes|' . $rule;
            }
        }

        return $rules;
    }
}
