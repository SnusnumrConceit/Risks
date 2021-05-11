<?php

namespace App\Http\Requests\Division;

use Illuminate\Validation\Rule;

class UpdateDivision extends StoreDivision
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
                'between:5,50',
                Rule::unique('divisions', 'name')->ignore($this->division->id)
            ],
            'parent_id' => [
                'nullable',
                'not_in:' . $this->division->id,
                'exists:divisions,id',
                'accessable'
            ]
        ];
    }
}
