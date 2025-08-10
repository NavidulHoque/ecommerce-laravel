<?php

namespace App\Http\Requests\Carts;

use App\Http\Requests\BaseCartRequest;

class StoreCartRequest extends BaseCartRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [

        ]);
    }
}
