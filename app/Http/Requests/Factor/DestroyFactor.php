<?php

namespace App\Http\Requests\Factor;

use Illuminate\Foundation\Http\FormRequest;

class DestroyFactor extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->factor->id]);
    }

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
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:factors,id|unique:factor_risk,factor_id'
        ];
    }

    /**
     * Валидационные сообщения
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.unique' => __('factors.in_use')
        ];
    }
}
