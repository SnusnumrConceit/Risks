<?php

namespace App\Http\Requests\Type;

use Illuminate\Validation\Rule;

class UpdateType extends StoreType
{
    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'between:5,100',
                Rule::unique('types')->ignore($this->type->id)
            ]
        ];
    }
}
