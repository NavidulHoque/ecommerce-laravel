<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function baseValidation(): array
    {
        return ['required', 'string'];
    }
}
