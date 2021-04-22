<?php

namespace App\Http\Requests\Role;

use Illuminate\Validation\Rule;

class UpdateRole extends StoreRole
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
                'name' => [
                    'required',
                    'between:5,50',
                    Rule::unique('roles', 'name')->ignore($this->role->id)
                ]
            ]
        );
    }
}
