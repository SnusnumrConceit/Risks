<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRole extends FormRequest
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
     * Подготовка данных перед валидацией
     */
    protected function prepareForValidation()
    {
        $this->merge(['uuid' => $this->role->uuid]);
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'uuid' => 'required|unique:users,role_uuid'
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
            'uuid*' => __('roles.in_use')
        ];
    }
}
