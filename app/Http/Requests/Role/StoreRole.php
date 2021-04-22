<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRole extends FormRequest
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
     * Правила валидации
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|between:5,50|unique:roles,name',
            'permissions'   => 'required|array|min:1',
            'permissions.*' => 'required|exists:permissions,id'
        ];
    }

    /**
     * Переводы атрибутов
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name'         => __('roles.name'),
            'permissions*' => __('permissions.permissions')
        ];
    }
}
