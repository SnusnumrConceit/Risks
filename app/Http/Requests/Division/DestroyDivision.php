<?php

namespace App\Http\Requests\Division;

use Illuminate\Foundation\Http\FormRequest;

class DestroyDivision extends FormRequest
{
    /**
     * Наличие доступа
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Подготовка к валидации
     */
    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->division->id]);
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:divisions,id|unique:divisions,parent_id|unique:division_risk,division_id'
        ];
    }

    /**
     * Сообщения валидации
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.unique' => __('divisions.in_use')
        ];
    }
}
