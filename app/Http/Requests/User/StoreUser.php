<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
            'last_name'      => 'required|between:2,40',
            'first_name'     => 'required|between:2,40',
            'middle_name'    => 'required|between:2,40',
            'appointment'    => 'required|between:5,50',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|between:8,50|confirmed',
            'role_uuid'      => 'required|exists:roles,uuid',
            'division_id'    => 'required|exists:divisions,id',
            'is_responsible' => 'nullable|boolean'
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
            'last_name'      => __('users.last_name'),
            'first_name'     => __('users.first_name'),
            'middle_name'    => __('users.middle_name'),
            'appointment'    => __('users.appointment'),
            'email'          => __('users.email'),
            'password'       => __('users.password'),
            'role_uuid'      => __('roles.role'),
            'divisions_id'   => __('divisions.division'),
            'is_responsible' => __('users.is_responsible'),
        ];
    }
}
