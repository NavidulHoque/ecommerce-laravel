<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;

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

    protected function findById($model, $id)
    {
        return $model::find($id);
    }

    protected function formatDateTime($datetime)
    {
        return Carbon::parse(time: $datetime)->format('Y-m-d H:i:s');;
    }

    protected function generateCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';

        for ($i = 0; $i < 6; $i++) {
            $result .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $result;
    }
}
