<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;

class UpdateUser extends StoreUser
{
    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                'email' => [
                    'required',
                    'between:5,50',
                    Rule::unique('users', 'email')->ignore($this->user->id)
                ],
                'password' => 'nullable|between:8,50|confirmed'
            ]
        );
    }
}
