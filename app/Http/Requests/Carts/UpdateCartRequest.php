<?php

namespace App\Http\Requests\Carts;

use App\Http\Requests\BaseCartRequest;

class UpdateCartRequest extends BaseCartRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'quantity' => 'required|integer|min:1',
        ]);
    }
}
