<?php

namespace App\Http\Controllers;

use App\Models\User;

abstract class Controller
{
    protected function baseValidation(): array
    {
        return ['required', 'string'];
    }

    protected function findUser($column, $operator)
    {
        return User::where($column, $operator)->first();
    }
}
