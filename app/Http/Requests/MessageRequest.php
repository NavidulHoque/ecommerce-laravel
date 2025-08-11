<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $messageRules = [
            'content'     => 'string',
            'receiver_id' => 'integer|exists:users,id',
            'isRead'      => 'boolean'
        ];

        if ($this->isMethod('post')) {

            foreach ($messageRules as $field => $rule) {
                if ($field !== 'isRead') {
                    $messageRules[$field] = 'required|' . $rule;
                }
            }

            $rules = $messageRules;
        }

        elseif ($this->isMethod('patch')) {

            foreach ($messageRules as $field => $rule) {

                if ($field !== 'receiver_id') {
                    $messageRules[$field] = 'sometimes|' . $rule;
                }
            }

            $rules = $messageRules;
        }

        return $rules;
    }
}
