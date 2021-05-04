<?php

namespace App\Http\Requests\Factor;

use App\Factor;
use Illuminate\Validation\Rule;

class UpdateFactor extends StoreFactor
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
                Rule::unique('factors', 'name')->ignore($this->factor->id)
            ],
            'parent_id' => [
                'nullable',
                'in:' . implode(',', $this->getOrphansIds()),
                'exists:factors,id,parent_id,' . null,
            ]
        ];
    }

    /**
     * Получить массив идентификаторов корневых элементов
     *
     * @return array
     */
    public function getOrphansIds() : array
    {
        return Factor::whereNull('parent_id')->pluck('id')->toArray();
    }
}
